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
    public function loginForm(Request $request)
    {
        if ($request->session()->get('it_admin_logged_in')) return redirect()->route('admin.panel');
        return view('admin-login');
    }

    public function loginPost(Request $request)
    {
        $kullanici = $request->input('kullanici_adi');
        $sifre = $request->input('sifre');

        $gecerliKullanici = config('kurumsal.admin_user');
        $gecerliSifre = config('kurumsal.admin_pass');

        if ($kullanici === $gecerliKullanici && $sifre === $gecerliSifre) {
            $request->session()->put('it_admin_logged_in', true);
            $request->session()->put('it_admin_user', $kullanici);
            return redirect()->route('admin.panel')->with('basari', 'IT Admin oturumu başarıyla açıldı.');
        }

        // YENİ: Başarısız admin girişi loglama (OWASP A09)
        Log::warning('Başarısız IT Admin giriş denemesi', [
            'kullanici_adi' => $kullanici,
            'ip' => $request->ip()
        ]);
        
        return redirect()->back()->with('hata', 'Hatalı kullanıcı adı veya şifre!');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['it_admin_logged_in', 'it_admin_user']);
        return redirect()->route('admin.login')->with('basari', 'Oturum kapatıldı.');
    }

    public function calisanLoginForm()
    {
        return view('calisan-login');
    }

    public function calisanLoginPost(Request $request)
    {
        $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if ($request->user() && is_null($request->user()->email_verified_at)) {
                $kod = random_int(100000, 999999);
                $request->session()->put('2fa_kodu', $kod);
                Mail::to($request->user()->email)->send(new TwoFactorMail($kod));
                return redirect()->route('2fa.form');
            }
            return redirect()->route('calisan.form')->with('basari', 'Sisteme başarıyla giriş yapıldı.');
        }
        
        // YENİ: Başarısız çalışan girişi loglama (OWASP A09)
        Log::warning('Başarısız çalışan giriş denemesi', [
            'email' => $request->input('email'),
            'ip' => $request->ip()
        ]);
        
        return back()->with('hata', 'E-posta veya şifre hatalı!');
    }

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