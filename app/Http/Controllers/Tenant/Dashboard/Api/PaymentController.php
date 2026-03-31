<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant\Dashboard\Api;

use App\Domain\Tenant\Dashboard\Api\Payment\Services\Interfaces\IPaymentService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\Dashboard\Api\PaymentResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PaymentController extends Controller
{
    public function __construct(
        protected IPaymentService $paymentService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        return PaymentResource::collection($this->paymentService->listAllPayments());
    }
}
