<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant\Home;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'showtime_id'    => ['required', 'integer', 'exists:showtimes,id'],
            'seat_ids'       => ['required', 'array',   'min:1'],
            'seat_ids.*'     => ['integer',  'exists:showtime_seats,id'],
            'user_id'        => ['nullable', 'integer', 'exists:users,id'],
            'customer'                    => ['required', 'array'],
            'customer.name'               => ['required', 'string', 'max:255'],
            'customer.email'              => ['required', 'email', 'max:255'],
            'customer.phone_country_code' => ['nullable', 'string', 'max:10'],
            'customer.phone'              => ['nullable', 'string', 'max:50'],
        ];
    }
}
