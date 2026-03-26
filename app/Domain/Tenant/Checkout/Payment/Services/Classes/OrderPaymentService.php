<?php

declare(strict_types=1);

namespace App\Domain\Tenant\Checkout\Payment\Services\Classes;

use App\Domain\Shared\Payments\Manager\PaymentManager;
use App\Domain\Tenant\Checkout\Payment\Repositories\Interfaces\IPaymentRepository;
use App\Domain\Tenant\Checkout\Payment\Services\Interfaces\IOrderPaymentService;
use App\Domain\Tenant\Home\Booking\Enums\BookingStatus;
use App\Domain\Tenant\Home\Booking\Services\Interfaces\IBookingService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderPaymentService implements IOrderPaymentService
{
    public function __construct(
        protected IBookingService $bookingService,
        protected IPaymentRepository $paymentRepository,
        protected PaymentManager $paymentManager
    ) {}

    public function initiatePayment(array $data): array
    {
        $booking = $this->getAndValidateBooking($data['booking_id']);

        $cartId = (string) Str::uuid();
        $payment = $this->createPendingPayment($booking, $cartId);

        $gatewayResponse = $this->paymentManager->initiate(
            $this->buildGatewayPayload($booking, $cartId)
        );

        return [
            'redirect_url' => $gatewayResponse->redirectUrl,
            'payment_id' => $payment->id,
        ];
    }

    public function handleCallback(array $data): bool
    {
        $this->logCallback($data);

        $cartId = $data['cart_id'] ?? null;
        if (! $cartId) {
            return false;
        }

        $payment = $this->findPaymentByCartId($cartId);
        if (! $payment) {
            return false;
        }

        $response = $this->paymentManager->handleCallback($data);
        $isSuccess = $response->isSuccessful;

        $this->updatePaymentStatus($payment, $data, $cartId, $isSuccess);

        if ($isSuccess) {
            $this->processSuccessfulBooking($payment->booking_id);
        }

        return $isSuccess;
    }

    protected function getAndValidateBooking(int|string $bookingId)
    {
        $booking = $this->bookingService->findBooking((int) $bookingId);

        if ($booking->status === BookingStatus::PAID) {
            throw new \InvalidArgumentException('Booking is already paid.');
        }

        return $booking;
    }

    protected function createPendingPayment($booking, string $cartId)
    {
        return $this->paymentRepository->create([
            'booking_id' => $booking->id,
            'amount' => $booking->total_price,
            'currency' => 'AED',
            'status' => 'pending',
            'gateway' => 'paytabs',
            'transaction_ref' => $cartId, // Using transaction_ref as our cart_id until callback provides actual ref
        ]);
    }

    protected function buildGatewayPayload($booking, string $cartId): array
    {
        return [
            'merchant' => 'paytabs',
            'amount' => (float) $booking->total_price,
            'cart_id' => $cartId,
            'description' => "Booking #{$booking->id} for movie: {$booking->showtime->movie->title}",
            'tenant_name' => $booking->customer?->name ?? 'Guest',
            'tenant_email' => $booking->customer?->email ?? 'guest@example.com',
            'callback_url' => url('/api/checkout/callback'),
            'return_url' => url("/api/booking/{$booking->id}/success"),
        ];
    }

    protected function logCallback(array $data): void
    {
        try {
            Log::info('Tenant order payment callback received', $data);
        } catch (\Throwable $e) {
            // Ignore logging errors
        }
    }

    protected function findPaymentByCartId(string $cartId)
    {
        return $this->paymentRepository->first(['transaction_ref' => $cartId]);
    }

    protected function updatePaymentStatus($payment, array $data, string $cartId, bool $isSuccess): void
    {
        $newStatus = $isSuccess ? 'success' : 'failed';
        $tranRef = $data['tran_ref'] ?? $cartId;

        $this->paymentRepository->updateWhere([
            'status' => $newStatus,
            'transaction_ref' => $tranRef,
            'payload' => $data,
        ], ['id' => $payment->id]);
    }

    protected function processSuccessfulBooking(int|string $bookingId): void
    {
        $this->bookingService->confirmBookingPayment((int) $bookingId);
    }
}
