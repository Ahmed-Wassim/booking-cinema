<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant\Dashboard\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'       => ['required', 'string', 'max:50', 'unique:discounts,code'],
            'type'       => ['required', 'string', 'in:percentage,fixed'],
            'value'      => ['required', 'numeric', 'min:0'],
            'max_uses'   => ['nullable', 'integer', 'min:1'],
            'starts_at'  => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active'  => ['nullable', 'boolean'],
        ];
    }
}
