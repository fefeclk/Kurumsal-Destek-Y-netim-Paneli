<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class WebTicketController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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

        $request->validate([
            'calisan_ad_soyad' => 'required|string|max:255', 
            'departman' => 'required|string', 
            'ariza_bildirimi' => 'required|string'
        ]);

        $ticket = Ticket::create([
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
            'ticket_kodu' => 'TCK-' . random_int(1000, 9999)
        ]);

        return redirect()->back()->with('basari', 'Talebiniz alınmıştır (Kayıt No: ' . $ticket->ticket_kodu . ').');
    }

    public function durumGuncelle(Request $request, Ticket $ticket)
    {
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');
        $ticket->update(['durum' => $request->input('yeni_durum')]);
        return redirect()->back()->with('basari', $ticket->ticket_kodu . ' kodlu kaydın durumu güncellendi!');
    }

    public function destroy(Request $request, Ticket $ticket)
    {
        $this->authorize('delete', $ticket);
        if (!$this->checkAdmin($request)) return redirect()->route('admin.login');
        $ticket->delete();
        return redirect()->back()->with('basari', 'Kayıt silindi.');
    }

    public function sifreSifirlamaTalep(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
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
            'ticket_kodu' => 'RST-' . random_int(1000, 9999)
        ]);

        return back()->with('basari', 'Şifre sıfırlama talebiniz Admin paneline (Ticket olarak) iletildi.');
    }

    public function sifreDegistir(Request $request)
    {
        if ($this->check2FA($request)) return redirect()->route('2fa.form');
        
        $request->validate(['yeni_sifre' => 'required|string|min:6']);
        $user = $request->user();
        $user->password = Hash::make($request->yeni_sifre);
        $user->save();
        
        return redirect()->back()->with('basari', '🔑 Şifreniz başarıyla güncellendi!');
    }
}