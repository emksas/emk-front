<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountingAccountRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'descripcion' => 'required|string|max:255',
            'usuario_cedula' => 'required|string|max:20',
        ];
    }

    public function messages()
    {
        return [
            'descripcion.required' => 'El campo descripción es obligatorio.',
            'descripcion.string' => 'El campo descripción debe ser una cadena de texto.',
            'descripcion.max' => 'El campo descripción no debe exceder los 255 caracteres.',
            'usuario_cedula.required' => 'El campo cédula de usuario es obligatorio.',
            'usuario_cedula.string' => 'El campo cédula de usuario debe ser una cadena de texto.',
            'usuario_cedula.max' => 'El campo cédula de usuario no debe exceder los 20 caracteres.',
        ];
    }


}

?> 