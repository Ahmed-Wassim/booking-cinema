<?php

namespace App\Domain\Landlord\Dashboard\Web\Payment\Repositories\Classes;

use App\Domain\Landlord\Dashboard\Web\Payment\Repositories\Interfaces\IPaymentRepository;
use App\Domain\Shared\Repositories\Classes\AbstractRepository;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;

class PaymentRepository extends AbstractRepository implements IPaymentRepository
{

    public function findByToken(string $token): ?Payment
    {
        return $this->model->where('payment_token', $token)->first();
    }

    public function findByRef(string $ref): ?Payment
    {
        return $this->model->where('transaction_ref', $ref)->first();
    }

    public function getByTenant(string $tenantId)
    {
        return $this->model->where('tenant_id', $tenantId)->orderBy('created_at', 'desc')->get();
    }

    public function updateStatus(int $id, string $status, array $extra = []): Model
    {
        $payment = $this->model->findOrFail($id);
        $payment->update(array_merge(['status' => $status], $extra));
        return $payment;
    }
}
