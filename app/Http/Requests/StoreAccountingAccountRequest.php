<?php 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAccountingAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'descripcion' => 'required|string|max:255',
            'usuario_cedula' => 'required|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'descripcion.required' => 'El campo descripción es obligatorio.',
            'descripcion.string' => 'El campo descripción debe ser una cadena de texto.',
            'descripcion.max' => 'El campo descripción no debe exceder los 255 caracteres.',
            'usuario_cedula.required' => 'El campo usuario_cedula es obligatorio.',
            'usuario_cedula.string' => 'El campo usuario_cedula debe ser una cadena de texto.',
            'usuario_cedula.max' => 'El campo usuario_cedula no debe exceder los 20 caracteres.',
        ];
    }

}

?> 