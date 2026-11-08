<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebTicketController;
use App\Http\Controllers\AuthController;

// 1. ÇALIŞAN GİRİŞ (LOGIN) ROTALARI
Route::get('/login', [AuthController::class, 'calisanLoginForm'])->name('login');
Route::post('/login', [WebTicketController::class, 'calisanLoginPost'])->middleware('throttle:5,1')->name('calisan.login.post');
Route::post('/logout', [WebTicketController::class, 'calisanLogout'])->name('calisan.logout');
Route::post('/sifre-sifirlama-talep', [WebTicketController::class, 'sifreSifirlamaTalep'])->name('calisan.sifre.talep');

// 2. KORUMALI ÇALIŞAN TİCKET EKRANI
Route::middleware('auth')->group(function () {
    Route::get('/', [WebTicketController::class, 'createForm'])->name('calisan.form');
    Route::post('/ticket-ekle', [WebTicketController::class, 'store'])->name('web.ticket.store');
    Route::post('/sifre-degistir', [WebTicketController::class, 'sifreDegistir'])->name('calisan.sifre.degistir');
});

// 2FA DOĞRULAMA ROTALARI
Route::middleware('auth')->group(function () {
    Route::get('/2fa-dogrulama', [WebTicketController::class, 'dogrulamaForm'])->name('2fa.form');
    Route::post('/2fa-dogrulama', [WebTicketController::class, 'dogrulamaPost'])->name('2fa.post');
});

// 3. IT ADMIN GİRİŞ ROTALARI
Route::get('/admin/login', [WebTicketController::class, 'loginForm'])->name('admin.login');
Route::post('/admin/login', [WebTicketController::class, 'loginPost'])->middleware('throttle:5,1')->name('admin.login.post');
Route::post('/admin/logout', [WebTicketController::class, 'logout'])->name('admin.logout');

// 4. KORUMALI IT ADMIN PANELİ
Route::prefix('admin')->group(function () {
    Route::get('/', [WebTicketController::class, 'adminPanel'])->name('admin.panel');
    Route::get('/sss', [WebTicketController::class, 'sss'])->name('admin.sss');
    Route::post('/calisan-ekle', [WebTicketController::class, 'calisanEkle'])->name('admin.calisan.ekle');
    Route::delete('/calisan-sil/{user}', [WebTicketController::class, 'calisanSil'])->name('admin.calisan.sil');
    
    Route::post('/sifre-sifirla-oto/{ticket}', [WebTicketController::class, 'sifreSifirlaOto'])->name('admin.sifre.oto');
    Route::post('/calisan-sifre-sifirla/{user}', [WebTicketController::class, 'calisanSifreSifirla'])->name('admin.calisan.sifirla');
    
    // YENİ: Admin Kendi Şifresini Değiştirme Rotası
    Route::post('/admin-sifre-degistir', [WebTicketController::class, 'adminSifreDegistir'])->name('admin.sifre.degistir');
    
    Route::post('/ticket/{ticket}/durum', [WebTicketController::class, 'durumGuncelle'])->name('web.ticket.status');
    Route::delete('/ticket/{ticket}', [WebTicketController::class, 'destroy'])->name('web.ticket.destroy');
});