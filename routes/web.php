<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/***********************Auth routes******************************************/
// Auth::routes();
// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
/****************************************************************************/

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'camp/{batch_id}'], function () {
    Route::get('/', 'CampController@campIndex');
    Route::get('/registration', 'CampController@campRegistration')->name('registration');
    Route::get('/query', 'CampController@campQueryRegistrationDataPage')->name('query');
    Route::post('/queryview', 'CampController@campViewRegistrationData')->name('queryview');
    Route::post('/queryupdate', 'CampController@campViewRegistrationData')->name('queryupdate');
    Route::post('/querysn', 'CampController@campGetApplicantSN')->name('querysn');
    Route::get('/queryadmit', 'CampController@campViewAdmission')->name('queryadmit');
    Route::post('/queryadmit', 'CampController@campQueryAdmission')->name('queryadmit');
    Route::post('/submit', 'CampController@campRegistrationFormSubmitted')->name('formSubmit');
});

Route::get('/backend', 'BackendController@masterIndex')->name('backendIndex');

Route::group(['prefix' => 'backend/{camp_id}', ], function () {
    Route::get('/', 'BackendController@campIndex')->name('campIndex');
    Route::get('/admission', 'BackendController@admission')->name('admission');
    Route::get('/registration', 'BackendController@showRegistration')->name('showRegistration');
});
