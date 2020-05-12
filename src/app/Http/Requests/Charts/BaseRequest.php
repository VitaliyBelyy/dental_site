<?php

namespace App\Http\Requests\Charts;

use BwtTeam\LaravelAPI\Requests\ApiRequest;
use Illuminate\Validation\Rule;
use App\Components\Filters\PeriodTypeFilter;

class BaseRequest extends ApiRequest
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
            'user_id'    => 'nullable|integer|exists:users,id',
            'patient_id' => 'nullable|integer|exists:patients,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'period'     => [
                'required',
                'string',
                Rule::in(PeriodTypeFilter::PERIODS),
            ],
        ];
    }
}
