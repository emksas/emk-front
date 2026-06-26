<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, \Illuminate\Contracts\Validation\Rule|array<mixed>|string>
     */
    protected function passwordRules(): array
    {
        return [
            'required',
            'string',
            Password::min(8)
                ->mixedCase()      // Mayúsculas y minúsculas
                ->numbers()        // Al menos un número
                ->symbols()        // Al menos un símbolo
                ->uncompromised(), // No aparece en filtraciones conocidas
            'confirmed',
        ];
    }
}