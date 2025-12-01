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
            'firstname'  => 'required|string|max:50',
            'middlename' => 'nullable|string|max:50',
            'lastname'   => 'required|string|max:50',
            'email'      => 'required|string|email|unique:users,email',
            'address'    => 'nullable|string',
            'bio'        => 'nullable|string',
            'is_active'  => 'boolean',
            'password'   => 'required|string|confirmed|min:8',
            'role_id'    => 'required|integer|exists:roles,id',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already taken.',
            'role.required' => 'Please select a role.',
        ];
    }
}
