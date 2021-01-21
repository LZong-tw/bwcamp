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
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
/****************************************************************************/

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'camp/{batch_id}'], function () {
    Route::get('/', 'CampController@campIndex');
    Route::get('/registration', 'CampController@campRegistration')->name('registration');
    Route::post('/registration', 'CampController@campRegistration')->name('registration');
    Route::get('/query', 'CampController@campQueryRegistrationDataPage')->name('query');
    Route::post('/queryview', 'CampController@campViewRegistrationData')->name('queryview');
    Route::post('/queryupdate', 'CampController@campViewRegistrationData')->name('queryupdate');
    Route::post('/querysn', 'CampController@campGetApplicantSN')->name('querysn');
    Route::get('/queryadmit', 'CampController@campViewAdmission')->name('queryadmit');
    Route::post('/queryadmit', 'CampController@campQueryAdmission')->name('queryadmit');
    Route::post('/downloadPaymentForm', 'CampController@downloadPaymentForm')->name('downloadPaymentForm');
    Route::post('/downloadCheckInNotification', 'CampController@downloadCheckInNotification')->name('downloadCheckInNotification');
    Route::post('/downloadCheckInQRcode', 'CampController@downloadCheckInQRcode')->name('downloadCheckInQRcode');
    Route::post('/submit', 'CampController@campRegistrationFormSubmitted')->name('formSubmit');
    Route::get('/downloads', 'CampController@showDownloads')->name('showDownloads');
});

Route::get('/backend', 'BackendController@masterIndex')->name('backendIndex');
Route::get('/jobs/{camp_id?}', 'BackendController@showJobs')->name('jobs');
Route::get('/failedJobsClear', 'BackendController@failedJobsClear')->name('failedJobsClear');
Route::get('/userlist/{camp_id?}', 'BackendController@userlist')->name('userlist');
Route::get('/user/editRole/{user_id}', 'BackendController@editRole')->name('editRole');
Route::post('/user/removeRole', 'BackendController@removeRole')->name('removeRole');
Route::post('/user/addRole', 'BackendController@addRole')->name('addRole');
Route::get('/rolelist/{camp_id?}', 'BackendController@rolelist')->name('rolelist');
Route::post('/listrole/removeRole', 'BackendController@listRemoveRole')->name('listRemoveRole');
Route::get('/listrole/addRole/{camp_id?}', 'BackendController@listAddRole')->name('listAddRole');
Route::post('/listrole/addRole/{camp_id?}', 'BackendController@listAddRole')->name('listAddRole');

Route::group(['prefix' => 'checkin', ], function () {
    Route::get('/', 'CheckInController@index');
    Route::get('/query', 'CheckInController@query');
    Route::post('/checkin', 'CheckInController@checkIn');
    Route::post('/un-checkin', 'CheckInController@uncheckIn');
    Route::post('/by_QR', 'CheckInController@by_QR');
    Route::get('/realtimeStat', 'CheckInController@realtimeStat');
    Route::get('/detailedStat', 'CheckInController@detailedStat');
});

Route::group(['prefix' => 'backend/campManage'], function(){
    Route::get('/list', 'BackendController@campManagement')->name("campManagement");
    Route::get('/batchList/{camp_id}', 'BackendController@showBatch')->name("showBatch");
});

Route::group(['prefix' => 'backend/{camp_id}', ], function () {
    Route::get('/', 'BackendController@campIndex')->name('campIndex');
    Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('permitted')->name('logs');
    Route::get('/registration/admission', 'BackendController@admission')->name('admission');
    Route::get('/registration/showPaymentForm/{applicant_id}', 'BackendController@showPaymentForm')->name('showPaymentForm');
    Route::get('/registration/batchAdmission', 'BackendController@batchAdmission')->name('batchAdmission');
    Route::post('/registration/showCandidate', 'BackendController@showCandidate')->name('showCandidate');
    Route::post('/registration/showBatchCandidate', 'BackendController@showBatchCandidate')->name('showBatchCandidate');
    Route::post('/registration/admission', 'BackendController@admission')->name('admission');
    Route::post('/registration/batchAdmission', 'BackendController@batchAdmission')->name('batchAdmission');
    Route::get('/registration', 'BackendController@showRegistration')->name('showRegistration');
    Route::get('/registration/list', 'BackendController@showRegistrationList')->name('showRegistrationList');
    Route::get('/changeBatchOrRegion', 'BackendController@changeBatchOrRegion')->name("changeBatchOrRegion");
    Route::post('/changeBatchOrRegion', 'BackendController@changeBatchOrRegion')->name("changeBatchOrRegion");
    Route::post('/registration/getList', 'BackendController@getRegistrationList')->name('getRegistrationList');
    Route::post('/registration/sendAdmittedMail', 'BackendController@sendAdmittedMail')->name('sendAdmittedMail');
    Route::post('/registration/sendCheckInMail', 'BackendController@sendCheckInMail')->name('sendCheckInMail');
    Route::get('/registration/groupList', 'BackendController@showGroupList')->name('showGroupList');
    Route::get('/registration/group/{batch_id}/{group}', 'BackendController@showGroup')->name('showGroup');
    Route::get('/statistics/appliedDate', 'BackendController@appliedDateStat')->name('appliedDateStat');
    Route::get('/statistics/gender', 'BackendController@genderStat')->name('genderStat');
    Route::get('/statistics/county', 'BackendController@countyStat')->name('countyStat');
    Route::get('/statistics/birthyear', 'BackendController@birthyearStat')->name('birthyearStat');
    Route::get('/statistics/batches', 'BackendController@batchesStat')->name('batchesStat');
    Route::get('/statistics/schoolOrCourse', 'BackendController@schoolOrCourseStat')->name('schoolOrCourseStat');
    Route::get('/statistics/admission', 'BackendController@admissionStat')->name('admissionStat');
    Route::get('/statistics/checkin', 'BackendController@checkinStat')->name('checkinStat');
    Route::get('/accounting', 'BackendController@showAccountingPage')->name('accounting');
    Route::get('/accounting/modify', 'BackendController@modifyAccounting')->name('modifyAccounting');
    Route::post('/accounting/modify', 'BackendController@modifyAccounting')->name('modifyAccounting');
    Route::get('/customMail', 'BackendController@customMail')->name('customMail');
    Route::get('/writeMail', 'BackendController@writeCustomMail')->name('writeMail');
    Route::post('/customMail/send', 'BackendController@sendCustomMail')->name('sendMail');
    Route::get('/customMail/selectMailTarget', 'BackendController@selectMailTarget')->name('selectMailTarget');
});
