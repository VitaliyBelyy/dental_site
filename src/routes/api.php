<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth
Route::group(['namespace' => 'Api\Auth', 'prefix' => 'auth'], function () {
    #POST	            /register		              auth.register
    Route::post('register', 'AuthController@register');
    #POST	            /login		                  auth.login
    Route::post('login', 'AuthController@login');

    Route::middleware(['auth:api'])->group(function () {
        #GET	            /user		                  auth.user
        Route::get('user', 'AuthController@user')->middleware('auth:api');
        #POST	            /email/verify/{id}		      verification.verify
        Route::post('email/verify/{id}', 'VerificationController@verify')->name('verification.verify');
        #POST	            /email/resend		          verification.resend
        Route::post('email/resend', 'VerificationController@resend')->name('verification.resend');
    });
});
