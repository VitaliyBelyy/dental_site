<?php

namespace App\Http\Requests\Users;

use BwtTeam\LaravelAPI\Requests\ApiRequest;
use App\Models\User;

class Index extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('viewList', User::class);
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
            'sort_by' => 'nullable|array',
            'sort_desc' => 'nullable|array',
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);
        return array_merge($data, $this->route()->parameters());
    }
}
