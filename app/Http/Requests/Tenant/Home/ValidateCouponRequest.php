<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant\Home;

use Illuminate\Foundation\Http\FormRequest;

class ValidateCouponRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'        => ['required', 'string', 'max:50'],
            'showtime_id' => ['required', 'integer', 'exists:showtimes,id'],
            'seat_ids'    => ['required', 'array', 'min:1'],
            'seat_ids.*'  => ['integer', 'exists:showtime_seats,id'],
        ];
    }
}
