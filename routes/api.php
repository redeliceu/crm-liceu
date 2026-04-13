<?php

use App\Http\Controllers\Api\CrmController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\TemplatesController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/crm/upload', [CrmController::class, 'store']);

Route::get('/crm/data', [CrmController::class, 'data']);

Route::post('/statistics/upload', [StatisticsController ::class, 'store']);

Route::get('/statistics/data', [StatisticsController ::class, 'data']);

Route::get('/users', [UserApiController::class, 'getAllUsers']);

Route::post('/user/{id}/flag/{flag_id}', [UserApiController::class, 'setUserFlag']);

Route::get('/test-broadcast', function () {
    broadcast(new \App\Events\TestEvent());
    return 'Evento enviado!';
});
