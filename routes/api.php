<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DirigenteController;
use App\Http\Controllers\Api\EventoController;
use App\Http\Controllers\Api\FinanceiroController;

// autenticação (sem proteção, mas com rate limiting mais restrito)
Route::middleware('throttle:5,15')->post('/auth/login', [AuthController::class, 'login']);

// rotas protegidas por token Sanctum com rate limiting padrão
Route::middleware(['auth:sanctum', 'throttle:100,60'])->group(function () {
    // autenticação
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // dirigentes
    Route::apiResource('dirigentes', DirigenteController::class);
    Route::get('/dirigentes/{uuid}/vinculos', [DirigenteController::class, 'vinculos']);

    // eventos
    Route::apiResource('eventos', EventoController::class);
    Route::post('/eventos/{evento}/participar', [EventoController::class, 'participar']);
    Route::post('/eventos/{evento}/checkin', [EventoController::class, 'checkin']);

    // financeiro
    Route::get('/financeiro/movimentos', [FinanceiroController::class, 'movimentos']);
    Route::post('/financeiro/movimentos', [FinanceiroController::class, 'storeMovimento']);
    Route::get('/financeiro/extrato', [FinanceiroController::class, 'extrato']);
    Route::get('/financeiro/saldo', [FinanceiroController::class, 'saldo']);
});
