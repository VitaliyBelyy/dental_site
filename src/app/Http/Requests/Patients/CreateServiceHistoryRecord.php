<?php

namespace App\Http\Requests\Patients;

use BwtTeam\LaravelAPI\Requests\ApiRequest;

class CreateServiceHistoryRecord extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $patient = $this->route('patient');

        return $patient && $this->user()->can('createServiceHistoryRecord', $patient);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'service_id' => 'required|integer|exists:services,id',
            'date' => 'required|date',
            'count' => 'required|integer|min:1',
        ];
    }
}
