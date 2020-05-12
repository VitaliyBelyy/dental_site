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
    # POST              /users                      users.store
    Route::post('users', 'UsersController@store');
    # GET               /users/{user}               users.show
    Route::get('users/{user}', 'UsersController@show');
    # PUT/PATCH         /users/{user}               users.update
    Route::put('users/{user}', 'UsersController@update');
    # DELETE            /users/{user}               users.destroy
    Route::delete('users/{user}', 'UsersController@destroy');

    // Patients
    # GET               /patients                                   patients.index
    Route::get('patients', 'PatientsController@index');
    # POST              /patients                                   patients.store
    Route::post('patients', 'PatientsController@store');
    # GET               /patients/{patient}                         patients.show
    Route::get('patients/{patient}', 'PatientsController@show');
    # PUT/PATCH         /patients/{patient}                         patients.update
    Route::put('patients/{patient}', 'PatientsController@update');
    # DELETE            /patients/{patient}                         patients.destroy
    Route::delete('patients/{patient}', 'PatientsController@destroy');

    # GET               /patients/{patient}/visit-history              patients.showVisitHistory
    Route::get('patients/{patient}/visit-history', 'PatientsController@showVisitHistory');
    # POST              /patients/{patient}/visit-history         patients.createVisitHistoryRecord
    Route::post('patients/{patient}/visit-history', 'PatientsController@createVisitHistoryRecord');

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

    // Visits
    # GET               /visits/{visit}/services     visits.showServices
    Route::get('visits/{visit}/services', 'VisitsController@showServices');

    // Charts
    # GET               /charts/visits                      charts.showVisits
    Route::get('charts/visits', 'ChartsController@showVisits');
    # GET               /charts/payments                    charts.showPayments
    Route::get('charts/payments', 'ChartsController@showPayments');
    # GET               /charts/services-cost               charts.showServicesCost
    Route::get('charts/services-cost', 'ChartsController@showServicesCost');
    # GET               /charts/ratio                       charts.showRatio
    Route::get('charts/ratio', 'ChartsController@showRatio');
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
