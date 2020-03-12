<?php

namespace App\Http\Requests\Events;

use BwtTeam\LaravelAPI\Requests\ApiRequest;
use Illuminate\Validation\Rule;

class Update extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'patient_id' => 'nullable|integer|exists:patients,id',
            'start' => 'nullable|date',
            'end' => 'nullable|date|after:start',
            'status' => [
                'nullable',
                'string',
                Rule::in(['Запланировано', 'Подтверждено', 'Выполнено', 'Пропущено', 'Перенесено']),
            ],
            'details' => 'nullable|string|max:500'
        ];
    }
}
