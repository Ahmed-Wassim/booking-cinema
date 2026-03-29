<?php

namespace App\Http\Requests\Tenant\Dashboard\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePriceTierRequest extends FormRequest
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
            'hall_id'     => ['required', 'integer', 'exists:halls,id'],
            'name'        => ['required', 'string', 'max:255'],
            'price'       => ['required', 'numeric', 'min:0'],
            'currency'    => ['required', 'string', 'exists:currencies,code'],
            'color'       => ['required', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['sometimes', 'boolean'],
        ];
    }
}
