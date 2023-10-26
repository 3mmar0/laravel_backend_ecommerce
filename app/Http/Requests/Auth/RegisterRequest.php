<?php

namespace App\Http\Requests\Auth;

use App\Http\Helpers\Helper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|min:3|max:100',
            'last_name' => 'required|min:3|max:100',
            'email' =>  'required|email|max:200|unique:users,email',
            'phone' => 'nullable|unique:users,phone|digits_between:10,20',
            'password' => 'required|min:6',
            'country' => 'nullable|string|min:2|max:2',
            'city' => 'nullable|string',
            'state' => 'nullable|string',
            'street' => 'nullable|string',
            'birthday' => 'nullable|date|before:today',
            'postal_code' => 'nullable|numeric',
            'lang' => 'nullable|string|min:2|max:3',
            'image' => 'nullable|image',
            'gender' => 'nullable|in:male,female',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        Helper::sendError('validation error', $validator->errors());
    }
}
