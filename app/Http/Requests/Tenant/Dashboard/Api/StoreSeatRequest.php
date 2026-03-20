<?php

namespace App\Http\Requests\Tenant\Dashboard\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreSeatRequest extends FormRequest
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
            'hall_id'       => ['required', 'integer', 'exists:halls,id'],
            'section_id'    => ['nullable', 'integer', 'exists:hall_sections,id'],
            'price_tier_id' => ['nullable', 'integer', 'exists:price_tiers,id'],
            'row'           => ['required', 'string', 'max:10'],
            'number'        => ['required', 'string', 'max:10'],
            'pos_x'         => ['required', 'numeric'],
            'pos_y'         => ['required', 'numeric'],
            'rotation'      => ['sometimes', 'numeric'],
            'width'         => ['sometimes', 'numeric'],
            'height'        => ['sometimes', 'numeric'],
            'shape'         => ['sometimes', 'string', \Illuminate\Validation\Rule::in(['rect', 'circle', 'sofa', 'wheelchair'])],
            'sort_order'    => ['sometimes', 'integer'],
            'is_active'     => ['sometimes', 'boolean'],
        ];
    }
}
