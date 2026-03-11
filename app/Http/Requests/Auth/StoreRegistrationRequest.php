<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class StoreRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'domain' => [
                'required', 
                'string', 
                'max:255', 
                'unique:domains,domain',
                'unique:registration_requests,domain',
                'regex:/^[a-zA-Z0-9\-]+$/'
            ],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255', 
                'unique:users,email',
                'unique:registration_requests,email'
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'plan_id' => ['required', 'exists:plans,id'],
        ];
    }
}
