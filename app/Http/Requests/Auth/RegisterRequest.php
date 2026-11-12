<?php

namespace App\Http\Requests\Auth;

use App\Enums\ScoutSection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'alpha_dash', 'min:3', 'max:40', Rule::unique('users', 'username')],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'birth_date' => ['required', 'date', 'before:today', 'after:1920-01-01'],
            'scout_section' => ['required', Rule::enum(ScoutSection::class)],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nombre y apellidos',
            'username' => 'nombre de usuario',
            'email' => 'correo electrónico',
            'birth_date' => 'fecha de nacimiento',
            'scout_section' => 'rama scout',
            'phone' => 'teléfono',
            'password' => 'contraseña',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'username.alpha_dash' => 'El nombre de usuario solo puede contener letras, números, guiones y guiones bajos.',
            'username.unique' => 'Ese nombre de usuario ya está en uso.',
            'email.unique' => 'Ya existe una cuenta registrada con ese correo electrónico.',
            'birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
}
