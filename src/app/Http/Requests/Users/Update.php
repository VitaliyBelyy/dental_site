<?php

namespace App\Http\Requests\Users;

use BwtTeam\LaravelAPI\Requests\ApiRequest;
use Illuminate\Support\Facades\Hash;

class Update extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->route('user');

        return $user && $this->user()->can('update', $user);
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
            'full_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'current_password' => [
                'required_with:new_password',
                function ($attribute, $value, $fail) {
                    if (auth()->user() && !Hash::check($value, auth()->user()->password)) {
                        $fail(trans('auth.failed'));
                    }
                },
            ],
            'new_password' => 'required_with:current_password|string|min:8|max:255'
        ];
    }
}
