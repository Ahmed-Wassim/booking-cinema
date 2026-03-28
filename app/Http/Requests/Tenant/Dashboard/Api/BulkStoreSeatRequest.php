<?php

namespace App\Http\Requests\Tenant\Dashboard\Api;

use Illuminate\Foundation\Http\FormRequest;

class BulkStoreSeatRequest extends FormRequest
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
            'seats'                 => ['required', 'array', 'min:1'],
            'seats.*.hall_id'       => ['required', 'integer', 'exists:halls,id'],
            'seats.*.section_id'    => ['nullable', 'integer', 'exists:hall_sections,id'],
            'seats.*.price_tier_id' => ['nullable', 'integer', 'exists:price_tiers,id'],
            'seats.*.row'           => ['required', 'string', 'max:10'],
            'seats.*.number'        => ['required', 'string', 'max:10'],
            'seats.*.pos_x'         => ['required', 'numeric'],
            'seats.*.pos_y'         => ['required', 'numeric'],
            'seats.*.rotation'      => ['sometimes', 'numeric'],
            'seats.*.width'         => ['sometimes', 'numeric'],
            'seats.*.height'        => ['sometimes', 'numeric'],
            'seats.*.shape'         => ['sometimes', 'string', \Illuminate\Validation\Rule::in(['rect', 'circle', 'sofa', 'wheelchair'])],
            'seats.*.sort_order'    => ['sometimes', 'integer'],
            'seats.*.is_active'     => ['sometimes', 'boolean'],
        ];
    }
}
