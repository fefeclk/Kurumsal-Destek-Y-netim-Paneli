<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function adminPanel(Request $request)
    {
        $tickets = Ticket::latest()->get();
        $calisanlar = User::latest()->get(); 
        return view('panel', compact('tickets', 'calisanlar'));
    }

    public function sss(Request $request)
    {
        return view('sss');
    }

    public function calisanEkle(Request $request)
    {
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
        $user->delete();
        return redirect()->back()->with('basari', 'Çalışan sistemden başarıyla silindi!');
    }

    public function sifreSifirlaOto(Request $request, Ticket $ticket)
    {
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
        $rastgeleSifre = Str::random(8);
        $user->password = Hash::make($rastgeleSifre);
        $user->save();
        return redirect()->back()->with('basari', "{$user->name} adlı çalışanın YENİ ŞİFRESİ: {$rastgeleSifre}");
    }

    public function adminSifreDegistir(Request $request)
    {
        $request->validate([
            'eski_sifre' => 'required',
            'yeni_sifre' => 'required|min:6'
        ]);
    /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->eski_sifre, $user->password)) {
            return back()->with('hata', 'Mevcut (eski) şifrenizi yanlış girdiniz!');
        }

        $user->password = Hash::make($request->yeni_sifre);
        $user->save();

        return back()->with('basari', 'Admin şifreniz başarıyla güncellendi!');
    }

    // YENİ: Arkadaşlarına Admin yetkisi verme veya yetkisini alma fonksiyonu
    public function yetkiDegistir(Request $request, User $user)
    {
        // Kendi kendini yanlışlıkla adminlikten düşürmeni engeller
        if (Auth::id() === $user->id) {
            return back()->with('hata', 'Kendi yetkinizi değiştiremezsiniz!');
        }

        // Yetkiyi tersine çevir (Adminse çalışan yap, çalışansa admin yap)
        $user->is_admin = !$user->is_admin;
        $user->save();

        $durum = $user->is_admin ? 'Admin yapıldı' : 'Standart çalışan yapıldı';
        return back()->with('basari', "{$user->name} adlı kullanıcının yetkisi güncellendi: {$durum}!");
    }
}