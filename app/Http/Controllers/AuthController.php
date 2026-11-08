<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorMail;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // YENİ: Tek bir giriş formumuz var (Eskiden iki taneydi)
    public function calisanLoginForm()
    {
        return view('calisan-login');
    }

    // YENİ: Akıllı Giriş Sistemi (Admini ve Çalışanı ayırır)
    public function calisanLoginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            // Giriş yapan kişi admin mi?
            if (Auth::user()->is_admin) {
                return redirect()->route('admin.panel')->with('basari', 'Yönetici Paneline Hoş Geldiniz!');
            }
            
            // Değilse standart çalışan sayfasına yolla
            return redirect()->route('calisan.form')->with('basari', 'Sisteme başarıyla giriş yaptınız.');
        }

        // Başarısız girişi loglama (OWASP A09)
        Log::warning('Başarısız giriş denemesi', [
            'email' => $request->input('email'),
            'ip' => $request->ip()
        ]);
        
        return back()->with('hata', 'E-posta veya şifre hatalı!');
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