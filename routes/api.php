<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ExternalTicketController;

Route::prefix('v1')->name('v1.')->group(function () {

    // Dış Rotalar: Partner Sistem / Halil Hoca'nın Bilgisayarı İçin (Middleware + Throttle Korumalı)
    Route::prefix('external')
        ->middleware(['throttle:60,1', 'api.key'])
        ->group(function () {
            Route::get('/tickets', [ExternalTicketController::class, 'index']);
            Route::get('/tickets/{ticket}', [ExternalTicketController::class, 'show']);
            // OWASP A01: Broken Access Control (API Koruması)
// Dışarıdan izinsiz veri yazılmasını ve silinmesini engeller
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/tickets', [ExternalTicketController::class, 'store']);
    
    // Eğer ExternalTicketController içinde update veya destroy varsa onları da bu bloğun içine almalısın:
    // Route::delete('/tickets/{id}', [ExternalTicketController::class, 'destroy']);
});
        });

    // Açık Test Rotası (Tarayıcıdan şifresiz hızlı kontrol gerekirse diye)
    Route::get('/public/tickets', [ExternalTicketController::class, 'index']);
});