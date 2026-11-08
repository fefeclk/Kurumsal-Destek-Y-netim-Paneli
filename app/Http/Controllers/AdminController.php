<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    private function checkAdmin(Request $request)
    {
        if (!$request->session()->get('it_admin_logged_in')) return false;
        return true;
    }

    public function adminPanel(Request $request)
    {
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');
        $tickets = Ticket::latest()->get();
        $calisanlar = User::latest()->get(); 
        return view('panel', compact('tickets', 'calisanlar'));
    }

    public function sss(Request $request)
    {
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');
        return view('sss');
    }

    public function adminSifreDegistir(Request $request)
    {
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');
        
        $request->validate([
            'eski_sifre' => 'required',
            'yeni_sifre' => 'required|min:6'
        ]);

        $mevcutSifre = config('kurumsal.admin_pass');

        if ($request->eski_sifre !== $mevcutSifre) {
            return back()->with('hata', 'Eski şifrenizi yanlış girdiniz!');
        }

        $path = base_path('.env');
        if (file_exists($path)) {
            $env = file_get_contents($path);
            if (strpos($env, 'ADMIN_PASS=') !== false) {
                $env = preg_replace('/^ADMIN_PASS=.*/m', 'ADMIN_PASS=' . $request->yeni_sifre, $env);
            } else {
                $env .= "\nADMIN_PASS=" . $request->yeni_sifre;
            }
            file_put_contents($path, $env);
        }
        
        return back()->with('basari', '🔒 IT Admin şifresi başarıyla değiştirildi! Yeni girişlerde bu şifreyi kullanın.');
    }

    public function calisanEkle(Request $request)
    {
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');
        
        $request->validate([
            'name' => 'required|string|max:255', 
            'email' => 'required|string|email|max:255|unique:users'
        ]);
        
        $rastgeleSifre = Str::random(8);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($rastgeleSifre);
        $user->save();

        return redirect()->back()->with('basari', "Kayıt Başarılı! {$request->name} için üretilen OTOMATİK ŞİFRE: {$rastgeleSifre}");
    }

    public function calisanSil(Request $request, User $user)
    {
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');
        $user->delete();
        return redirect()->back()->with('basari', 'Çalışan sistemden başarıyla silindi!');
    }

    public function sifreSifirlaOto(Request $request, Ticket $ticket)
    {
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');

        $user = User::find($ticket->kok_neden);
        if ($user) {
            $rastgeleSifre = Str::random(8);
            $user->password = Hash::make($rastgeleSifre);
            $user->save();
            $mesaj = "Talep onaylandı! {$user->name} adlı çalışanın YENİ ŞİFRESİ: {$rastgeleSifre} (Lütfen çalışana iletin)";
        } else {
            $mesaj = "Kullanıcı hesabı zaten silinmiş, sadece talep kapatıldı.";
        }
        $ticket->delete(); 
        return redirect()->back()->with('basari', $mesaj);
    }

    public function calisanSifreSifirla(Request $request, User $user)
    {
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');
        $rastgeleSifre = Str::random(8);
        $user->password = Hash::make($rastgeleSifre);
        $user->save();
        return redirect()->back()->with('basari', "{$user->name} adlı çalışanın YENİ ŞİFRESİ: {$rastgeleSifre}");
    }
}