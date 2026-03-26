<?php

namespace App\Http\Requests\Tenant\Dashboard\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShowtimeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hall_id' => ['sometimes', 'exists:tenant.halls,id'],
            'start_time' => ['sometimes', 'date'],
            'price_tier_id' => ['nullable', 'exists:tenant.price_tiers,id'],
            'status' => ['sometimes', 'in:active,inactive,cancelled'],
        ];
    }
}
