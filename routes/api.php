<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormFieldApiController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('getBatch', function (Request $request) {
   $batches = \App\Models\Batch::where('camp_id', \App\Models\Batch::find($request->id)->camp->id)->get();
   foreach($batches as &$batch) {
       $batch->name = $batch->name . " "  . (new \Carbon\Carbon($batch->batch_start))->translatedFormat("m/d(D)") . "~" . (new \Carbon\Carbon($batch->batch_end))->translatedFormat("m/d(D)");
   }
   return $batches->toArray();
});
Route::post('getFieldData', [FormFieldApiController::class, 'getFieldData']);