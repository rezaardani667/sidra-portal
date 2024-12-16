<?php

use App\Http\Controllers\GatewayConfigController;
use App\Http\Controllers\GatewayServiceController;
use App\Models\GatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::get('/config/{gateway_id}', [GatewayConfigController::class, 'config']);
    Route::post('/gateway-service/register', [GatewayServiceController::class, 'register']);
    Route::get('/get/gs/{data_plane_id}', [GatewayServiceController::class, 'getGatewayServices']);
});