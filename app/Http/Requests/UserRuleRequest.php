<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'role_id' => 'required|exists:roles,id',
            'team_id' => 'required|exists:teams,id',
            // Add more validation rules as needed
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'firstname.required' => 'The first name is required.',
            'lastname.required' => 'The last name is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Invalid email address format.',
            'email.unique' => 'The email address is already in use.',
            'role_id.required' => 'The role ID is required.',
            'role_id.exists' => 'Invalid role ID.',
            'team_id.required' => 'The team ID is required.',
            'team_id.exists' => 'Invalid team ID.',
            // Add more custom error messages as needed
        ];
    }
}
