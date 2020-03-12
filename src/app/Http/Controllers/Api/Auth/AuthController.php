<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Register as AuthRegister;
use App\Http\Requests\Auth\Login as AuthLogin;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    /**
     * Create a new user instance after a valid registration.
     *
     * @param AuthRegister $request
     *
     * @return ApiResponse
     */
    public function register(AuthRegister $request)
    {
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_token' => uniqid(Str::random(60)),
        ]);

        $user->assignRole('user');

        event(new Registered($user));

        return $this->respondWithToken($user);
    }

    /**
     * Get access token via given credentials.
     *
     * @param AuthLogin $request
     *
     * @return ApiResponse
     */
    public function login(AuthLogin $request)
    {
        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            return response()->api(null, 422);
        }

        $user = User::where('email', $request->email)->first();

        return $this->respondWithToken($user);
    }

    /**
     * Get authenticated User.
     *
     * @return ApiResponse
     */
    public function user()
    {
        $user = auth()->user();

        return $this->respondWithToken($user);
    }

    /**
     * Respond with structured user data.
     *
     * @param Authenticatable $user
     *
     * @return ApiResponse
     */
    private function respondWithToken(Authenticatable $user)
    {
        return response()->api([
            'full_name' => $user->full_name,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'access_token' => $user->api_token,
            'roles' => $user->roles->pluck('name')
        ]);
    }
}
