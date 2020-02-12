<?php

namespace App\Http\Requests\Users;

use BwtTeam\LaravelAPI\Requests\ApiRequest;
use App\Models\User;

class Restore extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('restore', User::class);
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
