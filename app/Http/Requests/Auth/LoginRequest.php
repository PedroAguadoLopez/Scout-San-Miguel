<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Introduce tu nombre de usuario.',
            'password.required' => 'Introduce tu contraseña.',
        ];
    }

    /**
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $credentials = [
            'username' => $this->string('username')->trim()->toString(),
            'password' => $this->string('password')->toString(),
        ];

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            throw ValidationException::withMessages([
                'username' => 'El nombre de usuario o la contraseña no son correctos.',
            ]);
        }
    }
}
