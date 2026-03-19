<?php

namespace App\Http\Requests\Tenant\Dashboard\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreHallRequest extends FormRequest
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
            'branch_id'   => ['required', 'integer', 'exists:branches,id'],
            'name'        => ['required', 'string', 'max:255'],
            'type'        => ['required', 'string', 'max:255'],
            'total_seats' => ['sometimes', 'integer', 'min:0'],
            'layout_type' => ['required', 'string', 'max:255'],
            'base_config' => ['nullable', 'array'],
        ];
    }
}
