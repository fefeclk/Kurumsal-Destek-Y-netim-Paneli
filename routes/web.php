<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebTicketController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

// 1. ÇALIŞAN GİRİŞ (LOGIN) ROTALARI
Route::get('/login', [AuthController::class, 'calisanLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'calisanLoginPost'])->middleware('throttle:5,1')->name('calisan.login.post');
Route::post('/logout', [AuthController::class, 'calisanLogout'])->name('calisan.logout');
Route::post('/sifre-sifirlama-talep', [WebTicketController::class, 'sifreSifirlamaTalep'])->middleware('throttle:6,1')->name('calisan.sifre.talep');

// 2. KORUMALI ÇALIŞAN TİCKET EKRANI
Route::middleware('auth')->group(function () {
    Route::get('/', [WebTicketController::class, 'createForm'])->name('calisan.form');
    Route::post('/ticket-ekle', [WebTicketController::class, 'store'])->name('web.ticket.store');
    Route::post('/sifre-degistir', [WebTicketController::class, 'sifreDegistir'])->middleware('throttle:6,1')->name('calisan.sifre.degistir');
});

// 2FA DOĞRULAMA ROTALARI
Route::middleware('auth')->group(function () {
    Route::get('/2fa-dogrulama', [AuthController::class, 'dogrulamaForm'])->name('2fa.form');
    Route::post('/2fa-dogrulama', [AuthController::class, 'dogrulamaPost'])->name('2fa.post');
});

// 3. IT ADMIN GİRİŞ ROTALARI
Route::get('/admin/login', [AuthController::class, 'loginForm'])->name('admin.login');
// EKSİK OLAN ADMIN GİRİŞ (POST) ROTASI EKLENDİ
Route::post('/admin/login', [AuthController::class, 'loginPost'])->middleware('throttle:5,1')->name('admin.login.post'); 
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// 4. KORUMALI IT ADMIN PANELİ
Route::prefix('admin')->group(function () {
    // ÇAKILMA YAPAN ESKİ adminPanel BURADAN DÜZELTİLDİ VE TEKE DÜŞÜRÜLDÜ
    Route::get('/', [AdminController::class, 'adminPanel'])->middleware('throttle:60,1')->name('admin.panel');
    Route::get('/sss', [AdminController::class, 'sss'])->name('admin.sss');
    Route::post('/calisan-ekle', [AdminController::class, 'calisanEkle'])->middleware('throttle:6,1')->name('admin.calisan.ekle');
    Route::delete('/calisan-sil/{user}', [AdminController::class, 'calisanSil'])->name('admin.calisan.sil');
    
    Route::post('/sifre-sifirla-oto/{ticket}', [AdminController::class, 'sifreSifirlaOto'])->middleware('throttle:6,1')->name('admin.sifre.oto');
    Route::post('/calisan-sifre-sifirla/{user}', [AdminController::class, 'calisanSifreSifirla'])->middleware('throttle:6,1')->name('admin.calisan.sifirla');
    
    // YENİ: Admin Kendi Şifresini Değiştirme Rotası
    Route::post('/admin-sifre-degistir', [AdminController::class, 'adminSifreDegistir'])->middleware('throttle:6,1')->name('admin.sifre.degistir');
    
    Route::post('/ticket/{ticket}/durum', [WebTicketController::class, 'durumGuncelle'])->name('web.ticket.status');
    Route::delete('/ticket/{ticket}', [WebTicketController::class, 'destroy'])->name('web.ticket.destroy');
});