<?php

namespace App\Http\Requests\Charts;

use BwtTeam\LaravelAPI\Requests\ApiRequest;
use App\Components\ChartDatasets\RatioChartDataset;
use Illuminate\Validation\Rule;

class ShowRatio extends ApiRequest
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
            'type' => [
                'required',
                'integer',
                Rule::in(RatioChartDataset::TYPES),
            ],
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);
        return array_merge($data, $this->route()->parameters());
    }
}
