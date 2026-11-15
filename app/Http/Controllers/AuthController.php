<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Mail\TwoFactorMail;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    // YENİ: Tek bir giriş formumuz var
    public function calisanLoginForm()
    {
        return view('calisan-login');
    }

    // YENİ: Akıllı Giriş Sistemi (Admini ve Çalışanı ayırır)
    public function calisanLoginPost(LoginRequest $request, AuthService $authService)
    {
        // 1. Süper Admin Kontrolü
        $superAdmin = $authService->superAdminKontrolu($request->email, $request->password);
        if ($superAdmin) {
            if ($superAdmin['status'] === 'hata') return back()->with('hata', $superAdmin['mesaj']);
            $request->session()->regenerate();
            return redirect()->route($superAdmin['route'])->with('basari', $superAdmin['mesaj']);
        }

        // 2. Standart Giriş
        $giris = $authService->standartGiris($request->only(['email', 'password']), $request->ip());
        if ($giris['status'] === 'hata') return back()->with('hata', $giris['mesaj']);

        $request->session()->regenerate();
        return redirect()->route($giris['route'])->with('basari', $giris['mesaj']);
    }

    // YENİ: Tek bir çıkış sistemimiz var
    public function calisanLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
    
    public function dogrulamaForm(Request $request)
    {
        if (!$request->user() || $request->user()->email_verified_at !== null) return redirect()->route('calisan.form');
        return view('2fa-dogrulama');
    }

    public function dogrulamaPost(Request $request)
    {
        $request->validate(['kod' => 'required|numeric']);
        if ($request->kod == $request->session()->get('2fa_kodu')) {
            $user = $request->user();
            $user->email_verified_at = now(); 
            $user->save();
            $request->session()->forget('2fa_kodu');
            return redirect()->route('calisan.form')->with('basari', '✅ E-posta başarıyla doğrulandı. Sisteme hoş geldiniz!');
        }
        return back()->with('hata', 'Hatalı doğrulama kodu girdiniz!');
    }
}