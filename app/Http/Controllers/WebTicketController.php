<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorMail;
use Illuminate\Support\Str;

class WebTicketController extends Controller
{
    private function check2FA(Request $request)
    {
        if ($request->user() && is_null($request->user()->email_verified_at)) return true;
        return false;
    }

    private function checkAdmin(Request $request)
    {
        if (!$request->session()->get('it_admin_logged_in')) return false;
        return true;
    }

    public function createForm(Request $request)
    {
        if ($this->check2FA($request)) return redirect()->route('2fa.form');
        return view('talep-olustur');
    }

    public function store(Request $request)
    {
        if ($this->check2FA($request)) return redirect()->route('2fa.form');

        $request->validate(['calisan_ad_soyad' => 'required\vert{}string\vert{}max:255', 'departman' => 'required\vert{}string', 'ariza_bildirimi' => 'required\vert{}string']);$ticket = Ticket::create([
            'calisan_ad_soyad' => $request->calisan_ad_soyad,
            'departman' => $request->departman,
            'ariza_bildirimi' => $request->ariza_bildirimi,
            'kategori' => $request->input('kategori', 'Genel Hata'),
            'onem_seviyesi' => $request->input('onem_seviyesi', 'Bilgi'),
            'kok_neden' => $request->input('kok_neden', 'Bilinmiyor'),
            'operasyonel_risk' => $request->input('operasyonel_risk', 'Bilinmiyor'),
            'etiketler' => $request->has('etiketler') ? implode(', ', $request->etiketler) : null,
            'yonetici_onayi_sart' => $request->has('yonetici_onayi_sart') ? true : false,
            'durum' => 'Açık (L1 Triyaj)',
            'ticket_kodu' => 'TCK-' . rand(1000, 9999)
        ]);

        return redirect()->back()->with('basari', 'Talebiniz alınmıştır (Kayıt No: ' . $ticket->ticket_kodu . ').');
    }

    public function loginForm(Request $request)
    {
        if ($request->session()->get('it_admin_logged_in')) return redirect()->route('admin.panel');
        return view('admin-login');
    }

    public function loginPost(Request $request)
    {
        $kullanici =$request->input('kullanici_adi');
        $sifre =$request->input('sifre');

        // Admin bilgilerini anında .env dosyasından çekiyoruz
        $gecerliKullanici = env('ADMIN_USER', 'admin');$gecerliSifre = env('ADMIN_PASS', '123456');

        if ($kullanici === $gecerliKullanici &&$sifre === $gecerliSifre) {$request->session()->put('it_admin_logged_in', true);
            $request->session()->put('it_admin_user',$kullanici);
            return redirect()->route('admin.panel')->with('basari', 'IT Admin oturumu başarıyla açıldı.');
        }

        return redirect()->back()->with('hata', 'Hatalı kullanıcı adı veya şifre!');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['it_admin_logged_in', 'it_admin_user']);
        return redirect()->route('admin.login')->with('basari', 'Oturum kapatıldı.');
    }

    public function adminPanel(Request $request)
    {
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');
        $tickets = Ticket::latest()->get();$calisanlar = User::latest()->get(); 
        return view('panel', compact('tickets', 'calisanlar'));
    }

    public function sss(Request $request)
    {
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');
        return view('sss');
    }

    // YENİ: Admin Şifresini Değiştirme (.env dosyasını yeniden yazar)
    public function adminSifreDegistir(Request $request)
    {
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');$request->validate([
            'eski_sifre' => 'required',
            'yeni_sifre' => 'required|min:6'
        ]);

        $mevcutSifre = env('ADMIN_PASS', '123456');

        if ($request->eski_sifre !==$mevcutSifre) {
            return back()->with('hata', 'Eski şifrenizi yanlış girdiniz!');
        }

        $path = base_path('.env');
        if (file_exists($path)) {
            $env = file_get_contents($path);
            if (strpos($env, 'ADMIN_PASS=') !== false) {$env = preg_replace('/^ADMIN_PASS=.*/m', 'ADMIN_PASS=' . $request->yeni_sifre,$env);
            } else {
                $env .= "\nADMIN_PASS=" . $request->yeni_sifre;
            }
            file_put_contents($path,$env);
        }
        
        return back()->with('basari', '🔒 IT Admin şifresi başarıyla değiştirildi! Yeni girişlerde bu şifreyi kullanın.');
    }

    public function durumGuncelle(Request $request, Ticket$ticket)
    {
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');
        $ticket->update(['durum' =>$request->input('yeni_durum')]);
        return redirect()->back()->with('basari', $ticket->ticket_kodu . ' kodlu kaydın durumu güncellendi!');
    }

    public function destroy(Request $request, Ticket$ticket)
    {
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');$ticket->delete();
        return redirect()->back()->with('basari', 'Kayıt silindi.');
    }

    public function calisanEkle(Request $request)
    {
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');
        $request->validate(['name' => 'required\vert{}string\vert{}max:255', 'email' => 'required\vert{}string\vert{}email\vert{}max:255\vert{}unique:users']);$rastgeleSifre = Str::random(8);

        $user = new User();
        $user->name =$request->name;
        $user->email =$request->email;
        $user->password = Hash::make($rastgeleSifre);$user->save();

        return redirect()->back()->with('basari', "Kayıt Başarılı! {$request->name} için üretilen OTOMATİK ŞİFRE: {$rastgeleSifre}");
    }

    public function calisanSil(Request $request, User$user)
    {
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');$user->delete();
        return redirect()->back()->with('basari', 'Çalışan sistemden başarıyla silindi!');
    }

    public function sifreSifirlamaTalep(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email',$request->email)->first();
        
        if (!$user) return back()->with('hata', 'Bu e-posta adresine ait bir hesap bulunamadı!');

        Ticket::create([
            'calisan_ad_soyad' => $user->name,
            'departman' => 'Sistem Erişimi Yok',
            'ariza_bildirimi' => "Hesap Erişimi Kaybı: Kullanıcı şifresini unuttuğunu bildiriyor ve sistemden yeni şifre talep ediyor. E-posta: {$user->email}",
            'kategori' => 'Şifre Sıfırlama Talebi',
            'onem_seviyesi' => 'Kritik',
            'kok_neden' => $user->id, 
            'operasyonel_risk' => 'Kullanıcı sisteme giriş yapamıyor.',
            'durum' => 'Açık (L1 Triyaj)',
            'ticket_kodu' => 'RST-' . rand(1000, 9999)
        ]);

        return back()->with('basari', 'Şifre sıfırlama talebiniz Admin paneline (Ticket olarak) iletildi.');
    }

    public function sifreSifirlaOto(Request $request, Ticket$ticket)
    {
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');

        $user = User::find($ticket->kok_neden);
        if ($user) {$rastgeleSifre = Str::random(8);
            $user->password = Hash::make($rastgeleSifre);
            $user->save();$mesaj = "Talep onaylandı! {$user->name} adlı çalışanın YENİ ŞİFRESİ: {$rastgeleSifre} (Lütfen çalışana iletin)";
        } else {
            $mesaj = "Kullanıcı hesabı zaten silinmiş, sadece talep kapatıldı.";
        }
        $ticket->delete(); 
        return redirect()->back()->with('basari', $mesaj);
    }

    public function calisanSifreSifirla(Request $request, User$user)
    {
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');
        $rastgeleSifre = Str::random(8);$user->password = Hash::make($rastgeleSifre);$user->save();
        return redirect()->back()->with('basari', "{$user->name} adlı çalışanın YENİ ŞİFRESİ: {$rastgeleSifre}");
    }

    public function calisanLoginForm()
    {
        return view('calisan-login');
    }

    public function calisanLoginPost(Request $request)
    {
        $credentials =$request->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::attempt($credentials)) {$request->session()->regenerate();
            if ($request->user() && is_null($request->user()->email_verified_at)) {$kod = rand(100000, 999999);
                $request->session()->put('2fa_kodu',$kod);
                Mail::to($request->user()->email)->send(new TwoFactorMail($kod));
                return redirect()->route('2fa.form');
            }
            return redirect()->route('calisan.form')->with('basari', 'Sisteme başarıyla giriş yapıldı.');
        }
        return back()->with('hata', 'E-posta veya şifre hatalı!');
    }

    public function calisanLogout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();$request->session()->regenerateToken();
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
        if ($request->kod == $request->session()->get('2fa_kodu')) {$user = $request->user();$user->email_verified_at = now(); 
            $user->save();$request->session()->forget('2fa_kodu');
            return redirect()->route('calisan.form')->with('basari', '✅ E-posta başarıyla doğrulandı. Sisteme hoş geldiniz!');
        }
        return back()->with('hata', 'Hatalı doğrulama kodu girdiniz!');
    }

    public function sifreDegistir(Request $request)
    {
        if ($this->check2FA($request)) return redirect()->route('2fa.form');
        $request->validate(['yeni_sifre' => 'required\vert{}string\vert{}min:6']);$user = $request->user();$user->password = Hash::make($request->yeni_sifre);$user->save();
        return redirect()->back()->with('basari', '🔑 Şifreniz başarıyla güncellendi!');
    }
}