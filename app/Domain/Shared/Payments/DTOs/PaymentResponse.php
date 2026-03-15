<?php

declare(strict_types=1);

namespace App\Domain\Shared\Payments\DTOs;

use App\Domain\Shared\DTO\DataTransferObject;

class PaymentResponse extends DataTransferObject
{
    public bool $isSuccessful;
    public ?string $redirectUrl = null;
    public ?string $transactionRef = null;
    public ?array $raw = null;

    public static function success(array $data = []): self
    {
        return new self(array_merge(['isSuccessful' => true], $data));
    }

    public static function failure(array $data = []): self
    {
        return new self(array_merge(['isSuccessful' => false], $data));
    }

    public function getRedirectUrl(): ?string
    {
        return $this->redirectUrl;
    }

    // satisfy abstract requirement
    public static function fromRequest(array $request): self
    {
        // we don't expect to build a response from a request; simply map
        return new self($request);
    }
}
