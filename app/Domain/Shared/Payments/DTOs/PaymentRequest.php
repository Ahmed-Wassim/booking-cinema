<?php

declare(strict_types=1);

namespace App\Domain\Shared\Payments\DTOs;

use App\Domain\Shared\DTO\DataTransferObject;

class PaymentRequest extends DataTransferObject
{
    public string $merchant; // e.g. 'paytabs'
    // we accept numeric strings as well to avoid assignment errors in callers
    public float|int|string $amount;
    public string $currency;
    public string $cart_id;
    public ?string $description;
    public ?string $tenant_name;
    public ?string $tenant_email;
    public ?string $return_url;
    public ?string $callback_url;

    public static function fromRequest(array $data): self
    {
        return new self([
            'merchant' => $data['merchant'] ?? 'paytabs',
            'amount' => (float) ($data['amount'] ?? 0),
            'currency' => $data['currency'] ?? config('paytabs.currency', 'AED'),
            'cart_id' => $data['cart_id'] ?? (string) \Illuminate\Support\Str::uuid(),
            'description' => $data['description'] ?? null,
            'tenant_name' => $data['tenant_name'] ?? null,
            'tenant_email' => $data['tenant_email'] ?? null,
            'return_url' => $data['return_url'] ?? null,
            'callback_url' => $data['callback_url'] ?? null,
        ]);
    }
}
