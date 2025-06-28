<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormFieldApiController;
use App\Http\Controllers\ApiController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('getBatch', [FormFieldApiController::class, 'getBatch']);
Route::post('getFieldData', [FormFieldApiController::class, 'getFieldData']);
Route::post('getCampData', [ApiController::class, 'sendCampData']);

Route::middleware('auth:web')->group(function () {
    Route::post('applicant/transfer', [\App\Http\Controllers\BackendController::class, 'transferApplicant'])->name('api.applicant.transfer');
    Route::get('batches/available', [\App\Http\Controllers\BackendController::class, 'getAvailableBatches'])->name('api.batches.available');
});