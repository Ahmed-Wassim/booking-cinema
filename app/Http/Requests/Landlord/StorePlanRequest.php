<?php

namespace App\Http\Requests\Landlord;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'exists:currencies,code'],
            'billing_interval' => ['required', 'string', 'in:monthly,yearly'],
            'features' => ['nullable', 'array'],
            'features.*.feature_key' => ['required', 'string', \Illuminate\Validation\Rule::enum(\App\Domain\Landlord\Enums\FeatureKeyEnum::class)],
            'features.*.feature_value' => ['required', 'string', 'max:255'],
        ];
    }
}
