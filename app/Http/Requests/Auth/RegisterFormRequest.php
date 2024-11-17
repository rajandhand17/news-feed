<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name'             => 'required|max:20|string',
            'last_name'              => 'required|string|max:20',
            'username'               => 'required|max:20|regex:/^[A-Za-z0-9_-]*$/|unique:users,username',
            'password'               => 'required|min:6',
            'password_confirmation'  => 'required|same:password',
            'email'                  => 'required|email|max:50|unique:users,email',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'     => false,
            'message'     => 'Validation errors',
            'errors'      => $validator->errors(),
        ], 422));
    }

    public function messages()
    {
        return [
            'email.required'                 => __('responses.email_field_required'),
            'email.email'                    => __('responses.valid_email_pattern'),
            'email.max'                      => __('responses.max_content_50'),
            'email.unique'                   => __('responses.unique_email'),
            'first_name.required'            => __('responses.first_name_field_required'),
            'first_name.max'                 => __('responses.max_content_20'),
            'first_name.string'              => __('responses.string_data_allowed'),
            'last_name.required'             => __('responses.last_name_field_required'),
            'last_name.max'                  => __('responses.max_content_20'),
            'last_name.string'               => __('responses.string_data_allowed'),
            'username.unique'                => __('responses.unique_username'),
            'username.max'                   => __('responses.max_content_20'),
            'username.required'              => __('responses.username_field_required'),
            'username.regex'                 => __('responses.username_regex_pattern'),
            'password.required'              => __('responses.password_required_field'),
            'password.min'                   => __('responses.min_content_6'),
            'password_confirmation.required' => __('responses.password_confirmation_required_field'),
            'password_confirmation.same'     => __('responses.match_confirmed_password'),
        ];
    }
}
