<?php

use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PPIC\ProductionPlanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login']);

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('/production-plans', ProductionPlanController::class);
    Route::put('/production-plans/{productionPlan}/approve', [ProductionPlanController::class, 'approvePlan']);
    Route::get('/report/production-plans', [ProductionPlanController::class, 'makeReport']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
