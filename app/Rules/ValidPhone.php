<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Propaganistas\LaravelPhone\Rules\Phone;

class ValidPhone implements ValidationRule, DataAwareRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected array $data = [];

    /**
     * The country field name or fixed country code.
     */
    protected ?string $countryField;

    /**
     * Create a new rule instance.
     *
     * @param string|null $countryField The name of the field containing the country code, or a hardcoded ISO code.
     */
    public function __construct(?string $countryField = null)
    {
        $this->countryField = $countryField;
    }

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $phoneRule = new Phone();

        if ($this->countryField) {
            $hasField = Arr::has($this->data, $this->countryField);

            if ($hasField) {
                $phoneRule->countryField($this->countryField);
            } else {
                $phoneRule->country($this->countryField);
            }
        } else {
            $phoneRule->country('AUTO');
        }

        $validator = Validator::make(
            $this->data,
            [$attribute => $phoneRule]
        );

        if ($validator->fails()) {
            $fail($validator->errors()->first($attribute));
        }
    }
}
