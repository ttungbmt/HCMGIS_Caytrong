<?php

use App\Http\Controllers\MapController;
use App\Http\Controllers\PopupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/map/config', [MapController::class, 'config']);
Route::post('/map/popup/nongho/modal', [PopupController::class, 'nonghoModal']);
Route::post('/map/popup/nongho', [PopupController::class, 'nongho']);
Route::post('/map/popup/thuadat', [PopupController::class, 'thuadat']);
Route::post('/map/popup/kd-nongsan', [PopupController::class, 'kd_nongsan']);
Route::post('/map/popup/kd-thuoc-bvtv', [PopupController::class, 'kd_thuoc_bvtv']);


