<?php

namespace App\Http\Requests\Patients;

use BwtTeam\LaravelAPI\Requests\ApiRequest;

class CreateVisitHistoryRecord extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $patient = $this->route('patient');

        return $patient && $this->user()->can('createVisitHistoryRecord', $patient);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => 'required|date',
            'services' => 'required|array',
            'services.*.id' => 'required|integer|exists:services,id',
            'services.*.service_count' => 'required|integer|min:1|max:10',
            'services.*.total_cost' => 'required|numeric',
        ];
    }
}
