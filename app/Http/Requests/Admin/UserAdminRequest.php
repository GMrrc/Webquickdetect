<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserAdminRequest extends FormRequest
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
            'nom' => "required|string|max:255|regex:/[A-Za-zÀ-ÖØ-öø-ÿ '-]+/",
            'prenom' => "required|string|max:255|regex:/[A-Za-zÀ-ÖØ-öø-ÿ '-]+/",
            'dateNaissance' => 'required|date',
            'role' => 'required|in:admin',
            'email' => 'required|string|email|max:255|unique:users',
            'motDePasse' => 'required|string|min:8|regex:/[0-9]/|regex:/[a-z]/|regex:/[A-Z]/|regex:/[!@#$%^&*().,\/]/',
        ];
    }
}
