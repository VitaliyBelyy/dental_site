<?php

namespace App\Http\Requests\Patients;

use BwtTeam\LaravelAPI\Requests\ApiRequest;
use Illuminate\Validation\Rule;
use App\Models\Patient;

class Store extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('store', Patient::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'gender' => [
                'nullable',
                'integer',
                Rule::in(Patient::GENDERS),
            ],
            'birth_date' => 'nullable|date',
            'anamnesis_id' => 'nullable|integer|exists:anamnesis,id',
            'medical_info' => 'nullable|string|max:1000'
        ];
    }
}
