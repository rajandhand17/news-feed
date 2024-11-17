<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserPreferenceRequest extends FormRequest
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
            'sources' => 'array|nullable',
            'categories' => 'array|nullable',
            'authors' => 'array|nullable',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors(),
        ], 422));
    }

    public function messages()
    {
        return [
            'sources.array'         => __('responses.source_array'),
            'categories.array'      => __('responses.categories_array'),
            'authors.array'         => __('responses.authors_array'),
            'sources.nullable'      => __('responses.source_nullable'),
            'categories.nullable'   => __('responses.categories_nullable'),
            'authors.nullable'      => __('responses.authors_nullable'),
            
        ];
    }
}
