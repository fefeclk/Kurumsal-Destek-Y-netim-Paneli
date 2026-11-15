<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Mail\TwoFactorMail;

class AuthController extends Controller
{
    // YENİ: Tek bir giriş formumuz var
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

        // YENİ: SÜPER ADMIN (İlk Kurulum / Acil Durum) KAPISI
        if ($request->email === 'admin@admin.com') {
            
            // Sistemde 'admin@admin.com' HARİCİNDE yetkili (is_admin = true) biri var mı?
            $gercekAdminVarMi = User::where('is_admin', true)->where('email', '!=', 'admin@admin.com')->exists();
            
            // Eğer gerçek bir çalışan Admin yapılmışsa, bu arka kapıyı betonla kapat!
            if ($gercekAdminVarMi) {
                return back()->with('hata', 'Güvenlik: Sistemde gerçek bir yönetici bulunduğu için varsayılan "Süper Admin" girişi devre dışı bırakılmıştır!');
            }

            // Başka admin yoksa ve şifre 'admin' girildiyse sistemi kurtar
            if ($request->password === 'admin') {
                $superAdmin = User::firstOrCreate(
                    ['email' => 'admin@admin.com'],
                    [
                        'name' => 'IT Süper Admin', 
                        'password' => Hash::make('admin'), 
                        'is_admin' => true, 
                        'email_verified_at' => now() // Doğrulama istemesin diye onaylı doğurtuyoruz
                    ]
                );
                
                Auth::login($superAdmin);
                $request->session()->regenerate();
                
                return redirect()->route('admin.panel')->with('basari', '⚠️ Acil durum Süper Admin hesabıyla girdiniz. Sistemin güvenliği için lütfen kayıtlı bir çalışanı Admin yapın!');
            }
        }

        // STANDART GİRİŞ SİSTEMİ (Gerçek Çalışanlar ve Atanmış Adminler İçin)
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $request->session()->regenerate();

            // Giriş yapan kişi admin yetkisine sahip mi?
            if (Auth::user()->is_admin) {
                return redirect()->route('admin.panel')->with('basari', 'Yönetici Paneline Hoş Geldiniz!');
            }
            
            // Standart çalışansa kendi paneline yolla
            return redirect()->route('calisan.form')->with('basari', 'Sisteme başarıyla giriş yaptınız.');
        }

        // DÜZELTİLDİ: Başarısız girişi loglama (OWASP) - Çift Illuminate hatası giderildi
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