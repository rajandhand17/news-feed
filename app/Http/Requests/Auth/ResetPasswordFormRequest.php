<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class ResetPasswordFormRequest extends FormRequest
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
            'email'                 => 'required|email|'.Rule::exists('users', 'email')->where(function ($query) {
                $query->whereNull('deleted_at');
            }),
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'otp'   => 'required',
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
            'email.required'                 => __('responses.email_field_required'),
            'email.email'                    => __('responses.valid_email_pattern'),
            'email.exists'                   => __('responses.not_exists_email'),
            'password.required'              => __('responses.password_required_field'),
            'password.min'                   => __('responses.min_content_6'),
            'password_confirmation.required' => __('responses.password_confirmation_required_field'),
            'password_confirmation.same'     => __('responses.match_confirmed_password'),
            'otp.required'                   => __('responses.otp_required'),
        ];
    }
}
