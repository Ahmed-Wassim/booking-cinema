<?php

declare(strict_types=1);

namespace App\Domain\Landlord\DTO;

use App\Domain\Shared\DTO\DataTransferObject;

class PaymentDTO extends DataTransferObject
{
    public ?string $tenantId;
    public ?int    $registrationId;
    public ?int    $planId;
    public ?float  $amount;
    public ?string $currency;
    public ?string $tenantName;
    public ?string $tenantEmail;
    public ?string $returnUrl;
    public ?string $callbackUrl;
    public ?string $cartId;

    public static function fromRequest(array $data): self
    {
        return new self([
            'tenantId'       => $data['tenant_id'] ?? null,
            'registrationId' => isset($data['registration_id']) ? (int) $data['registration_id'] : null,
            'planId'         => isset($data['plan_id']) ? (int) $data['plan_id'] : null,
            'amount'       => isset($data['amount']) ? (float) $data['amount'] : null,
            'currency'     => $data['currency'] ?? config('paytabs.currency', 'AED'),
            'tenantName'   => $data['tenant_name'] ?? null,
            'tenantEmail'  => $data['tenant_email'] ?? null,
            'returnUrl'   => $data['return_url'] ?? null,
            'callbackUrl' => $data['callback_url'] ?? null,
            'cartId'      => $data['cart_id'] ?? null,
        ]);
    }

}
