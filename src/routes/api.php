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
Route::group(['namespace' => 'Api', 'middleware' => 'auth:api'], function () {
    // Users
    # GET               /users                      users.index
    Route::get('users', 'UsersController@index');
    # DELETE            /users/{id}                 users.destroy
    Route::delete('users/{id}', 'UsersController@destroy');
    # POST              /users/{id}/restore         users.restore
    Route::post('users/{id}/restore', 'UsersController@restore');

    // Patients
    # GET               /patients                                   patients.index
    Route::get('patients', 'PatientsController@index');
    # POST              /patients                                   patients.store
    Route::post('patients', 'PatientsController@store');
    # GET               /patients/{patient}                         patients.show
    Route::get('patients/{patient}', 'PatientsController@show');
    # PUT/PATCH         /patients/{patient}                         patients.update
    Route::put('patients/{patient}', 'PatientsController@update');

    # GET               /patients/{id}/service-history              patients.showServiceHistory
    Route::get('patients/{id}/service-history', 'PatientsController@showServiceHistory');
    # POST              /patients/{patient}/service-history         patients.createServiceHistoryRecord
    Route::post('patients/{patient}/service-history', 'PatientsController@createServiceHistoryRecord');

    # GET               /patients/{patient}/payment-history         patients.showPaymentHistory
    Route::get('patients/{patient}/payment-history', 'PatientsController@showPaymentHistory');
    # POST              /patients/{patient}/payment-history         patients.createPaymentHistoryRecord
    Route::post('patients/{patient}/payment-history', 'PatientsController@createPaymentHistoryRecord');

    // Anamnesis
    # GET               /anamnesis                   anamnesis.index
    Route::get('anamnesis', 'AnamnesisController@index');

    // Services
    # GET               /services                    services.index
    Route::get('services', 'ServicesController@index');
    # POST              /services                    services.store
    Route::post('services', 'ServicesController@store');
    # PUT/PATCH         /services/{service}          services.update
    Route::put('services/{service}', 'ServicesController@update');
    # DELETE            /services/{service}          services.destroy
    Route::delete('services/{service}', 'ServicesController@destroy');

    // Events
    # GET               /events                      events.index
    Route::get('events', 'EventsController@index');
    # POST              /events                      events.store
    Route::post('events', 'EventsController@store');
    # PUT/PATCH         /events/{event}              events.update
    Route::put('events/{event}', 'EventsController@update');
    # DELETE            /events/{event}              events.destroy
    Route::delete('events/{event}', 'EventsController@destroy');
});

// Auth
Route::group(['namespace' => 'Api\Auth', 'prefix' => 'auth'], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');

    Route::middleware(['auth:api'])->group(function () {
        Route::get('user', 'AuthController@user')->middleware('auth:api');
        Route::post('email/verify/{id}', 'VerificationController@verify')->name('verification.verify');
        Route::post('email/resend', 'VerificationController@resend')->name('verification.resend');
    });

    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'ResetPasswordController@reset');
});
