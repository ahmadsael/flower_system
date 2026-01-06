<?php

namespace App\Http\Requests\Customer\Auth;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'unique:customers,email'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'max:20',
                'string',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[@$!%*?&])[A-Za-z\\d@$!%*?&]{8,20}$/',
            ],
            'password_confirmation' => ['required'],
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['required', 'in:male,female'],
            'birthday' => ['nullable', 'date'],
            'age' => ['nullable', 'integer', 'min:0', 'max:120'],
        ];
    }

    protected function getRedirectUrl(): string
    {
        return route('home', ['section' => 'register']);
    }
}
