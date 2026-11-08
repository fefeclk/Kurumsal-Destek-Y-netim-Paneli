<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebTicketController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\IsAdminMiddleware; // YENİ: Middleware buraya eklendi

// 1. ORTAK GİRİŞ (LOGIN) ROTALARI
Route::get('/login', [AuthController::class, 'calisanLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'calisanLoginPost'])->middleware('throttle:5,1')->name('calisan.login.post');
Route::post('/logout', [AuthController::class, 'calisanLogout'])->name('calisan.logout');
Route::post('/sifre-sifirlama-talep', [WebTicketController::class, 'sifreSifirlamaTalep'])->middleware('throttle:6,1')->name('calisan.sifre.talep');

// Eski admin rotalarına girmeye çalışanları ana login ekranına yönlendir
Route::get('/admin/login', function() { return redirect()->route('login'); })->name('admin.login');
Route::post('/admin/logout', [AuthController::class, 'calisanLogout'])->name('admin.logout');

// 2. KORUMALI ÇALIŞAN TİCKET EKRANI
Route::middleware('auth')->group(function () {
    Route::get('/', [WebTicketController::class, 'createForm'])->name('calisan.form');
    Route::post('/ticket-ekle', [WebTicketController::class, 'store'])->name('web.ticket.store');
    Route::post('/sifre-degistir', [WebTicketController::class, 'sifreDegistir'])->middleware('throttle:6,1')->name('calisan.sifre.degistir');
});

// 3. 2FA DOĞRULAMA ROTALARI
Route::middleware('auth')->group(function () {
    Route::get('/2fa-dogrulama', [AuthController::class, 'dogrulamaForm'])->name('2fa.form');
    Route::post('/2fa-dogrulama', [AuthController::class, 'dogrulamaPost'])->name('2fa.post');
});

// 4. KORUMALI IT ADMIN PANELİ
Route::prefix('admin')->middleware(['auth', IsAdminMiddleware::class])->group(function () {
    Route::post('/admin-sifre-degistir', [AdminController::class, 'adminSifreDegistir'])->name('admin.sifre.degistir');
    Route::get('/', [AdminController::class, 'adminPanel'])->name('admin.panel');
    Route::get('/sss', [AdminController::class, 'sss'])->name('admin.sss');
    Route::post('/calisan-ekle', [AdminController::class, 'calisanEkle'])->name('admin.calisan.ekle');
    Route::delete('/calisan-sil/{user}', [AdminController::class, 'calisanSil'])->name('admin.calisan.sil');
    
    Route::post('/yetki-degistir/{user}', [AdminController::class, 'yetkiDegistir'])->name('admin.yetki.degistir');
    
    Route::post('/sifre-sifirla-oto/{ticket}', [AdminController::class, 'sifreSifirlaOto'])->name('admin.sifre.oto');
    Route::post('/calisan-sifre-sifirla/{user}', [AdminController::class, 'calisanSifreSifirla'])->name('admin.calisan.sifirla');
    Route::post('/ticket/{ticket}/durum', [WebTicketController::class, 'durumGuncelle'])->name('web.ticket.status');
    Route::delete('/ticket/{ticket}', [WebTicketController::class, 'destroy'])->name('web.ticket.destroy');
});