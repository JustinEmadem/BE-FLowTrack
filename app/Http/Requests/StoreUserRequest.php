<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'firstname'     => 'required|string|max:50',
            'middlename'    => 'string|max:50',
            'lastname'      => 'required|string|max:50',
            'email'         => 'required|string|unique:users,email',
            'address'       => 'string',
            'bio'           => 'string',
            'is_active'     => 'boolean',
            'password'      => 'required|string|confirmed'
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already taken.',
        ];
    }
}
