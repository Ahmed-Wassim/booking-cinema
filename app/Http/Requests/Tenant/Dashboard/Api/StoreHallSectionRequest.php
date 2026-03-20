<?php

namespace App\Http\Requests\Tenant\Dashboard\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreHallSectionRequest extends FormRequest
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
            'layout_type' => ['nullable', 'string', 'max:255'],
            'base_config' => ['nullable', 'array'],
            'sort_order'  => ['sometimes', 'integer'],
        ];
    }
}
