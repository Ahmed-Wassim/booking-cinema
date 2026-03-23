<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Checkout\Payment\Services\Classes;

use App\Domain\Shared\Payments\Manager\PaymentManager;
use App\Domain\Tenant\Checkout\Payment\Repositories\Interfaces\IPaymentRepository;
use App\Domain\Tenant\Checkout\Payment\Services\Interfaces\IOrderPaymentService;
use App\Domain\Tenant\Home\Booking\Enums\BookingStatus;
use App\Domain\Tenant\Home\Booking\Repositories\Interfaces\IBookingRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderPaymentService implements IOrderPaymentService
{
    public function __construct(
        protected IBookingRepository $bookingRepository,
        protected IPaymentRepository $paymentRepository,
        protected PaymentManager $paymentManager
    ) {}

    public function initiatePayment(array $data): array
    {
        $booking = $this->bookingRepository->firstOrFail(
            conditions: ['id' => $data['booking_id']],
            relations: ['showtime.movie', 'customer']
        );

        if ($booking->status === BookingStatus::PAID) {
            throw new \InvalidArgumentException('Booking is already paid.');
        }

        $cartId = (string) Str::uuid();

        // Save pending payment record to associate it with cartId
        $payment = $this->paymentRepository->create([
            'booking_id' => $booking->id,
            'amount' => $booking->total_price,
            'currency' => 'AED',
            'status' => 'pending',
            'gateway' => 'paytabs',
            'transaction_ref' => $cartId, // Using transaction_ref as our cart_id until callback provides actual ref
        ]);

        $defaultReturn = '';
        $defaultCallback = '';
        try {
            $defaultReturn = route('tenant.payment.return', ['id' => $booking->id]);
            $defaultCallback = route('tenant.payment.callback');
        } catch (\Throwable $th) {
        }

        $gatewayResponse = $this->paymentManager->initiate([
            'merchant' => 'paytabs',
            'amount' => (float) $booking->total_price,
            'cart_id' => $cartId,
            'description' => "Booking #{$booking->id} for movie: {$booking->showtime->movie->title}",
            'tenant_name' => $booking->customer?->name ?? 'Guest',
            'tenant_email' => $booking->customer?->email ?? 'guest@example.com',
            'callback_url' => url('/api/checkout/callback'),
            'return_url' => url("/booking/{$booking->id}/success"),
        ]);

        return [
            'redirect_url' => $gatewayResponse->redirectUrl,
            'payment_id' => $payment->id,
        ];
    }

    public function handleCallback(array $data): bool
    {
        try {
            Log::info('Tenant order payment callback received', $data);
        } catch (\Throwable $e) {
        }

        $cartId = $data['cart_id'] ?? null;
        if (! $cartId) {
            return false;
        }

        // We mapped cart_id to transaction_ref on pending creation
        $payment = $this->paymentRepository->first(['transaction_ref' => $cartId]);
        if (! $payment) {
            return false;
        }

        $response = $this->paymentManager->handleCallback($data);
        $isSuccess = $response->isSuccessful;

        $newStatus = $isSuccess ? 'success' : 'failed';
        $tranRef = $data['tran_ref'] ?? $cartId;

        $this->paymentRepository->updateWhere([
            'status' => $newStatus,
            'transaction_ref' => $tranRef,
            'payload' => $data,
        ], ['id' => $payment->id]);

        if ($isSuccess) {
            $booking = $this->bookingRepository->firstOrFail(
                conditions: ['id' => $payment->booking_id],
                relations: ['seats.showtimeSeat']
            );
            $booking->update(['status' => BookingStatus::PAID->value]);

            foreach ($booking->seats as $bookingSeat) {
                if ($bookingSeat->showtimeSeat) {
                    $bookingSeat->showtimeSeat->update(['status' => 'booked']);
                }
            }
        }

        return $isSuccess;
    }
}
