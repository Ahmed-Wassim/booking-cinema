<?php

use App\Domain\Tenant\Checkout\Payment\Services\Classes\OrderPaymentService;
use App\Domain\Shared\Payments\DTOs\PaymentResponse;
use App\Domain\Shared\Payments\Manager\PaymentManager;
use App\Domain\Tenant\Home\Booking\Services\Interfaces\IBookingService;

class DummyCheckoutPaymentRepository implements \App\Domain\Tenant\Checkout\Payment\Repositories\Interfaces\IPaymentRepository
{
    public $lastData;

    public function create(array $data)
    {
        $this->lastData = $data;
        $model = new \App\Models\Tenant\Payment($data);
        $model->id = 999;
        return $model;
    }

    public function first(array $conditions)
    {
        return null;
    }

    public function updateWhere(array $values, array $conditions)
    {
        return true;
    }
}

class DummyBookingService implements IBookingService
{
    public function findBooking(int $id)
    {
        $booking = new stdClass();
        $booking->id = $id;
        $booking->total_price = 100.00;
        $booking->currency = 'USD';
        $booking->status = 'pending';
        $booking->showtime = (object) ['movie' => (object) ['title' => 'Test movie']];
        $booking->customer = (object) ['name' => 'Test', 'email' => 'test@example.com'];
        return $booking;
    }

    public function createBooking(array $data)
    {
        return new \App\Models\Tenant\Booking($data);
    }

    public function confirmBookingPayment(int $bookingId)
    {
        $booking = new \App\Models\Tenant\Booking();
        $booking->id = $bookingId;
        $booking->status = 'paid';
        return $booking;
    }
}

class DummyPaymentManager extends PaymentManager
{
    public bool $initiated = false;
    public array $lastRequest = [];

    public function __construct()
    {
        // skip parent constructor
    }

    public function initiate(\App\Domain\Shared\Payments\DTOs\PaymentRequest $req): PaymentResponse
    {
        $this->initiated = true;
        $this->lastRequest = [
            'amount' => $req->amount,
            'currency' => $req->currency,
            'cart_id' => $req->cart_id,
        ];

        return PaymentResponse::success(['redirectUrl' => 'https://example.test/ok']);
    }

    public function handleCallback(array $payload): PaymentResponse
    {
        return PaymentResponse::success(['raw' => $payload]);
    }
}

it('uses header currency in payment and stores to payment record', function () {
    $bookingService = new DummyBookingService();
    $paymentRepo = new DummyCheckoutPaymentRepository();
    $paymentManager = new DummyPaymentManager();
    $service = new OrderPaymentService($bookingService, $paymentRepo, $paymentManager);

    $result = $service->initiatePayment(['booking_id' => 42, 'currency' => 'USD']);

    expect($paymentRepo->lastData['currency'])->toBe('USD')
        ->and($paymentManager->initiated)->toBeTrue()
        ->and($paymentManager->lastRequest['currency'])->toBe('USD')
        ->and($result['redirect_url'])->toBe('https://example.test/ok');
});
