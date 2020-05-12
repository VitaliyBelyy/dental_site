<?php

namespace App\Http\Requests\Services;

use BwtTeam\LaravelAPI\Requests\ApiRequest;

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
            'name' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',
        ];
    }
}
