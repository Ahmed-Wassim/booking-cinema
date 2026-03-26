<?php

namespace App\Http\Requests\Tenant\Dashboard\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'movie_id' => ['required', 'integer'],
        ];
    }
}
