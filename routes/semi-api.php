<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormFieldApiController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\SemiApiController;

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
Route::get('groupCreation', [SemiApiController::class, 'groupCreation']);
Route::get('getBatchGroups', [SemiApiController::class, 'getBatchGroups']);
Route::post('setGroup', [SemiApiController::class, 'setGroup']);
Route::get('getCampOrganizations', [SemiApiController::class, 'getCampOrganizations']);
Route::get('getCampVolunteers', [SemiApiController::class, 'getCampVolunteers']);
