<?php

namespace App\Http\Requests\Users;

use BwtTeam\LaravelAPI\Requests\ApiRequest;
use App\Models\User;

class Destroy extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('destroy', User::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }
}
