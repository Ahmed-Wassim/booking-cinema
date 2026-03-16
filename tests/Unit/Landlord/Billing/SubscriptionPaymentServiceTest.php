<?php

/**
 * @property mixed $paymentRepo
 * @property mixed $subscriptionRepo
 * @property DummyPaymentManager $pm
 * @property SubscriptionPaymentService $service
 */

use App\Domain\Landlord\Billing\Payment\Services\SubscriptionPaymentService;
use App\Domain\Landlord\Enums\PaymentStatusEnum;
use App\Domain\Shared\Payments\DTOs\PaymentResponse;
use App\Domain\Shared\Payments\Manager\PaymentManager;

// simple dummy implementations of the repositories used by the service
class DummyPaymentRepository implements \App\Domain\Landlord\Repositories\Interfaces\IPaymentRepository
{
    public $lastData;
    public $updated;

    public function findByToken(string $token): ?\App\Models\Payment
    {
        $p = new \App\Models\Payment();
        $p->id = 123;
        return $p;
    }
    public function findByRef(string $ref): ?\App\Models\Payment
    {
        $p = new \App\Models\Payment();
        $p->id = 123;
        return $p;
    }
    public function getByTenant(string $tenantId)
    {
        return null;
    }
    public function updateStatus(int $id, string $status, array $extra = []): \Illuminate\Database\Eloquent\Model
    {
        $this->updated = compact('id', 'status', 'extra');
        $p = new \App\Models\Payment();
        $p->id = $id;
        return $p;
    }
    public function create(array $data): \App\Models\Payment
    {
        $this->lastData = $data;
        $p = new \App\Models\Payment($data);
        $p->id = 123;
        return $p;
    }
}

class DummySubscriptionRepository implements \App\Domain\Landlord\Repositories\Interfaces\ISubscriptionRepository
{
    public function findPendingByTenantAndPlan(string $tenantId, int $planId): ?\Illuminate\Database\Eloquent\Model
    {
        return null;
    }

    public function activate(int $id, ?int $paymentId = null): \Illuminate\Database\Eloquent\Model
    {
        return (object) [];
    }
}

// a tiny concrete subclass used for tests
class DummyPaymentManager extends PaymentManager
{
    public bool $initiated = false;
    public bool $handled = false;

    public function __construct()
    {
        // bypass parent constructor
    }

    public function initiate(\App\Domain\Shared\Payments\DTOs\PaymentRequest $req): PaymentResponse {
        $this->initiated = true;
        return PaymentResponse::success(['redirectUrl' => 'https://foo.bar']);
    }

    public function handleCallback(array $payload): PaymentResponse {
        $this->handled = true;
        return PaymentResponse::success();
    }
}


it('starts payment and returns redirect data', function () {
    $paymentRepo = new DummyPaymentRepository();
    $subscriptionRepo = new DummySubscriptionRepository();
    $pm = new DummyPaymentManager();
    $service = new SubscriptionPaymentService($paymentRepo, $subscriptionRepo, $pm);

    $result = $service->initiatePayment([
        'plan' => (object) ['id' => 5, 'name' => 'Basic', 'price' => 77.7],
        'tenantName' => 'Bob',
        'tenantEmail' => 'bob@test',
        'amount' => 77.7,
        'currency' => 'AED',
    ]);

    expect($result['redirect_url'])->toBe('https://foo.bar')
        ->and($result['payment_id'])->toBe(123);
    expect($pm->initiated)->toBeTrue();
    expect($paymentRepo->lastData['status'])->toBe(PaymentStatusEnum::PENDING->value);
});

it('handles callback and updates status', function () {
    $paymentRepo = new DummyPaymentRepository();
    $subscriptionRepo = new DummySubscriptionRepository();
    $pm = new DummyPaymentManager();
    $service = new SubscriptionPaymentService($paymentRepo, $subscriptionRepo, $pm);

    $success = $service->handleCallback(['payment_result' => ['response_status' => 'A'], 'cart_id' => 'abc']);
    expect($success)->toBeTrue();
    expect($pm->handled)->toBeTrue();
    expect($paymentRepo->updated['status'])->toBe(PaymentStatusEnum::PAID->value);
});
