<?php

use App\Http\Controllers\Api\CrmController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\UserApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
| Todas as rotas abaixo exigem usuário logado.
| O front (Inertia/axios, mesmo domínio) autentica pela sessão via Sanctum
| (statefulApi() em bootstrap/app.php), enviando o cookie de sessão e o X-XSRF-TOKEN.
*/
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/crm/upload', [CrmController::class, 'store'])
        ->middleware('throttle:20,1');

    Route::get('/crm/data', [CrmController::class, 'data']);

    Route::post('/statistics/upload', [StatisticsController::class, 'store'])
        ->middleware('throttle:20,1');

    Route::get('/statistics/data', [StatisticsController::class, 'data']);

    Route::get('/users', [UserApiController::class, 'getAllUsers']);

    Route::post('/user/{id}/flag/{flag_id}', [UserApiController::class, 'setUserFlag'])
        ->whereNumber(['id', 'flag_id']);
});
