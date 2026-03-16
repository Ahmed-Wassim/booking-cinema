<?php

namespace App\Http\Requests\Landlord;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTenantRequest extends FormRequest
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
    protected function prepareForValidation(): void
    {
        $centralDomain = config('tenancy.central_domains')[0];
        if ($this->has('domain') && !str_ends_with($this->domain, '.' . $centralDomain)) {
            $this->merge([
                'domain' => $this->domain . '.' . $centralDomain,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'string', 'max:255', Rule::unique('tenants', 'id')->ignore($this->route('tenant'))],
            'domain' => ['required', 'string', 'max:255', Rule::unique('domains', 'domain')->ignore($this->route('tenant'), 'tenant_id')],
            'plan_id' => ['required', 'exists:plans,id'],
        ];
    }
}
