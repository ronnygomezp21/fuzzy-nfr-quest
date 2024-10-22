<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'birth_date' => 'required|date',
            'password' => 'required|string|min:8',
            'role' => 'required|integer|exists:roles,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo name es requerido.',
            'last_name.required' => 'El campo last_name es requerido.',
            'username.required' => 'El campo username es requerido.',
            'username.unique' => 'El usuario ingresado ya existe.',
            'email.required' => 'El campo email electrónico es requerido.',
            'email.unique' => 'El correo electrónico ingresado ya existe.',
            'birth_date.required' => 'El campo birth_date es requerido.',
            'password.required' => 'El campo password es requerido.',
            'role.required' => 'El campo role es requerido.',
            'role.exists' => 'El rol seleccionado no existe en la base de datos.',
        ];
    }
}
