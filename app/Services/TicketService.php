<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function tumTicketlariGetir()
    {
        return Ticket::latest()->paginate(15);
    }

    public function yeniTicketOlustur(array $data): Ticket
    {
        return DB::transaction(function () use ($data) {
            $metin = mb_strtolower($data['ariza_bildirimi'] ?? '');
            $etiketDizisi = $data['etiketler'] ?? [];
            $etiketMetni = is_array($etiketDizisi) ? implode(', ', $etiketDizisi) : $etiketDizisi;
            $onaySart = isset($data['yonetici_onayi_sart']) && $data['yonetici_onayi_sart'] == '1';

            // Eğer Frontend AI internet kesintisi yüzünden boş gönderdiyse Backend Akıllı Kural Motoru devreye girer:
            $kategori = !empty($data['kategori']) ? $data['kategori'] : $this->otomatikKategoriBul($metin);
            $onem = !empty($data['onem_seviyesi']) ? $data['onem_seviyesi'] : ($onaySart || str_contains($etiketMetni, 'Kesintisi') ? 'Kritik' : 'Uyarı');
            $kokNeden = !empty($data['kok_neden']) ? $data['kok_neden'] : $this->otomatikKokNedenBul($metin, $etiketMetni);
            $risk = !empty($data['operasyonel_risk']) ? $data['operasyonel_risk'] : $this->otomatikRiskBul($metin, $etiketMetni);

            return Ticket::create([
                'ticket_kodu' => 'TCK-' . random_int(1000, 9999),
                'calisan_ad_soyad' => $data['calisan_ad_soyad'] ?? 'Kurum Çalışanı',
                'departman' => $data['departman'],
                'kategori' => $kategori,
                'onem_seviyesi' => $onem,
                'ariza_bildirimi' => $data['ariza_bildirimi'],
                'kok_neden' => $kokNeden,
                'operasyonel_risk' => $risk,
                'etiketler' => $etiketMetni ?: 'AI Otomatik Triyaj',
                'yonetici_onayi_sart' => $onaySart,
                'durum' => $onaySart ? 'Bloke Edildi (Onay Bekliyor)' : 'Açık (L1 Triyaj)',
            ]);
        });
    }

    private function otomatikKategoriBul(string $metin): string
    {
        if (str_contains($metin, 'fatura') || str_contains($metin, 'erp') || str_contains($metin, 'muhasebe')) return 'ERP & Veritabanı Performans';
        if (str_contains($metin, 'port') || str_contains($metin, 'yetki') || str_contains($metin, 'şifre')) return 'Siber Güvenlik & Erişim Yönetimi';
        if (str_contains($metin, 'sıcaklık') || str_contains($metin, 'sunucu') || str_contains($metin, 'internet') || str_contains($metin, 'ağ')) return 'Sistem Odası & Ağ Altyapısı';
        return 'Kurumsal Yazılım & Operasyon Destek';
    }

    private function otomatikKokNedenBul(string $metin, string $etiket): string
    {
        if (str_contains($metin, 'fatura') || str_contains($metin, 'donuyor')) return 'Sürüm güncellemesi sonrası oluşan veritabanı sorgu kilitlenmesi (deadlock) ve bellek aşımı.';
        if (str_contains($metin, 'port') || str_contains($metin, 'dış')) return 'Standart güvenlik duvarı (Firewall) politikalarını aşan kontrolsüz dış erişim talebi.';
        if (str_contains($etiket, 'Donanım')) return 'İklimlendirme/sensör ağ geçidi iletişim kopukluğu veya donanım bileşeni arızası.';
        return 'Servis yanıt süresinde (SLA) aşım yaratan uygulama katmanı senkronizasyon hatası.';
    }

    private function otomatikRiskBul(string $metin, string $etiket): string
    {
        if (str_contains($etiket, 'Finansal') || str_contains($metin, 'fatura')) return 'Faturalama ve sevkiyat süreçlerinin durması, yasal/finansal ceza riski.';
        if (str_contains($etiket, 'KVKK') || str_contains($metin, 'port')) return 'Yetkisiz dış erişim sonucu veri sızıntısı ve KVKK/ISO27001 denetim ihlali.';
        return 'İlgili departmanda iş sürekliliği kaybı ve operasyonel darboğaz oluşması.';
    }
}