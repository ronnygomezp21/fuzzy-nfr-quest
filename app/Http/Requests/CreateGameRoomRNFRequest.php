<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class CreateGameRoomRNFRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'expiration_date' => 'required|date|after:today',
            'questions' => 'required|array|min:1',
            'questions.*.nfr' => 'required|string',
            'questions.*.variable' => 'required|string',
            'questions.*.feedback1' => 'required|string',
            'questions.*.value' => 'required|string',
            'questions.*.feedback2' => 'required|string',
            'questions.*.recomend' => 'required|string',
            'questions.*.other_recommended_values' => 'required|string',
            'questions.*.feedback3' => 'required|string',
            'questions.*.validar' => 'required|string',
        ];
    }


    public function messages()
    {
        return [
            'expiration_date.required' => 'El campo fecha de finalizacion es obligatorio.',
            'expiration_date.date' => 'El campo fecha de finalizacion debe ser una fecha válida.',
            'expiration_date.after' => 'La fecha de finalizacion debe ser posterior a hoy.',
            'questions.required' => 'El arreglo de preguntas es obligatorio.',
            'questions.array' => 'Las preguntas deben ser enviadas en un formato de arreglo.',
            'questions.min' => 'Debes ingresar al menos una pregunta.',
            'questions.*.nfr.required' => 'El campo NFR es obligatorio.',
            'questions.*.variable.required' => 'El campo Variable es obligatorio.',
            'questions.*.feedback1.required' => 'El campo Feedback1 es obligatorio.',
            'questions.*.value.required' => 'El campo Valor es obligatorio.',
            'questions.*.feedback2.required' => 'El campo Feedback2 es obligatorio.',
            'questions.*.recomend.required' => 'El campo Recomendación es obligatorio.',
            'questions.*.other_recommended_values.required' => 'El campo Otros Valores es obligatorio.',
            'questions.*.feedback3.required' => 'El campo Feedback3 es obligatorio.',
            'questions.*.validar.required' => 'El campo Validar es obligatorio.',
        ];
    }
}
