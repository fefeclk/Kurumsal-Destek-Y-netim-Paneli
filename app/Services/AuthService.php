<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService
{
    public function superAdminKontrolu($email, $password)
    {
        if ($email !== 'admin@admin.com') return false;

        $gercekAdminVarMi = User::where('is_admin', true)->where('email', '!=', 'admin@admin.com')->exists();
        if ($gercekAdminVarMi) {
            return ['status' => 'hata', 'mesaj' => 'Güvenlik: Sistemde gerçek bir yönetici bulunduğu için varsayılan "Süper Admin" girişi devre dışı bırakılmıştır!'];
        }

        if ($password === 'admin') {
            $superAdmin = User::firstOrNew(['email' => 'admin@admin.com']);
            if (!$superAdmin->exists) {
                $superAdmin->name = 'IT Süper Admin';
                $superAdmin->password = Hash::make('admin');
                $superAdmin->email_verified_at = now();
                $superAdmin->is_admin = true;
                $superAdmin->save();
            }
            
            Auth::login($superAdmin);
            session()->regenerate(); // OWASP A07: Oturum Sabitleme (Session Fixation) Koruması
            
            return ['status' => 'basari', 'route' => 'admin.panel', 'mesaj' => '⚠️ Acil durum Süper Admin hesabıyla girdiniz.'];
        }

        return false;
    }

    public function standartGiris($credentials, $ip)
    {
        if (Auth::attempt($credentials)) {
            
            session()->regenerate(); // OWASP A07: Oturum Sabitleme (Session Fixation) Koruması
            
            return Auth::user()->is_admin 
                ? ['status' => 'basari', 'route' => 'admin.panel', 'mesaj' => 'Yönetici Paneline Hoş Geldiniz!']
                : ['status' => 'basari', 'route' => 'calisan.form', 'mesaj' => 'Sisteme başarıyla giriş yaptınız.'];
        }

        Log::warning('Başarısız giriş denemesi', ['email' => $credentials['email'], 'ip' => $ip]);
        return ['status' => 'hata', 'mesaj' => 'E-posta veya şifre hatalı!'];
    }
}