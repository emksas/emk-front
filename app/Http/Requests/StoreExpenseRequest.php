<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
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
            'valor' => 'required|numeric',
            'descripcion' => 'required|string|max:255',
            'fecha' => 'required|date',
            'estado' => 'nullable|string',
            'idPlanificacion' => 'nullable|integer',
            'cuentaContable_id' => 'required',
        ];
    }


    protected function prepareForValidation(): void
    {
        $this->merge([
            'estado' => $this->input('estado', 'pay'),
            'idPlanificacion' => $this->input('idPlanificacion', 1),
            'cuentaContable_id' => $this->input('cuentaContable_id', 1),
        ]);

    }

    public function messages(): array
    {
        return [
            'valor.required' => 'El campo valor es obligatorio.',
            'valor.numeric' => 'El campo valor debe ser un número.',
            'descripcion.required' => 'El campo descripción es obligatorio.',
            'descripcion.string' => 'El campo descripción debe ser una cadena de texto.',
            'descripcion.max' => 'El campo descripción no debe exceder los 255 caracteres.',
            'fecha.required' => 'El campo fecha es obligatorio.',
            'fecha.date' => 'El campo fecha debe ser una fecha válida.',
            'cuentaContable_id.required' => 'El campo cuenta contable es obligatorio.',
        ];
    }
}
