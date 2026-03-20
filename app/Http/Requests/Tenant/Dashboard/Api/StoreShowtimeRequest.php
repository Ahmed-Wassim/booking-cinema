<?php

namespace App\Http\Requests\Tenant\Dashboard\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreShowtimeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'movie_id' => ['required', 'exists:tenant.movies,id'],
            'hall_id' => ['required', 'exists:tenant.halls,id'],
            'start_time' => ['required', 'date'],
            'price_tier_id' => ['nullable', 'exists:tenant.price_tiers,id'],
        ];
    }
}
