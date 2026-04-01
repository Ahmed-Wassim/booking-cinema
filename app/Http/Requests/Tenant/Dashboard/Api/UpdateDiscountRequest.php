<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant\Dashboard\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDiscountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'       => ['sometimes', 'string', 'max:50', Rule::unique('discounts', 'code')->ignore($this->route('discount'))],
            'type'       => ['sometimes', 'string', 'in:percentage,fixed'],
            'value'      => ['sometimes', 'numeric', 'min:0'],
            'max_uses'   => ['nullable', 'integer', 'min:1'],
            'starts_at'  => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active'  => ['nullable', 'boolean'],
        ];
    }
}
