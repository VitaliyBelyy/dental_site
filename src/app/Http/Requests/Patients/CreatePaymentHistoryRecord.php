<?php

namespace App\Http\Requests\Patients;

use BwtTeam\LaravelAPI\Requests\ApiRequest;

class CreatePaymentHistoryRecord extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $patient = $this->route('patient');

        return $patient && $this->user()->can('createPaymentHistoryRecord', $patient);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'notes' => 'nullable|string'
        ];
    }
}
