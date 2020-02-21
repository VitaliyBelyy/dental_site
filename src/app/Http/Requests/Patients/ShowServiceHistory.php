<?php

namespace App\Http\Requests\Patients;

use BwtTeam\LaravelAPI\Requests\ApiRequest;
use App\Models\Patient;

class ShowServiceHistory extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $patient = Patient::find($this->route('id'));

        return $patient && $this->user()->can('viewServiceHistory', $patient);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'limit' => 'nullable|integer|min:1|max:50',
            'page' => 'nullable|integer|min:1',
            'q' => 'nullable|string|max:255',
            'sort_by' => 'nullable|string|max:64',
            'sort_desc' => 'nullable|string|max:5'
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);
        return array_merge($data, $this->route()->parameters());
    }
}
