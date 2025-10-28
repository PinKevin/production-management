<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PPIC\ProductionPlanController;
use App\Http\Controllers\Api\Production\ProductionOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('/production-plans', ProductionPlanController::class);
    Route::put(
        '/production-plans/{productionPlan}/approve',
        [ProductionPlanController::class, 'approvePlan']
    );
    Route::get('/report/production-plans', [ProductionPlanController::class, 'makeReport']);

    Route::get('/production-orders', [ProductionOrderController::class, 'index']);
    Route::get(
        '/production-orders/{productionOrder}',
        [ProductionOrderController::class, 'show']
    );
    Route::put(
        '/production-orders/{productionOrder}/change-status',
        [ProductionOrderController::class, 'changeStatus']
    );
    Route::get('/report/production-orders', [ProductionOrderController::class, 'makeReport']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
