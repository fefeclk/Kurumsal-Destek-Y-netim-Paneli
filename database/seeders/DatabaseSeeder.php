<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ticket;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Ticket::create([
            'ticket_kodu' => 'TCK-1042',
            'calisan_ad_soyad' => 'Ahmet Yılmaz',
            'departman' => 'Finans ve Muhasebe',
            'kategori' => 'ERP & Veritabanı Performans',
            'onem_seviyesi' => 'Kritik',
            'ariza_bildirimi' => 'Sabah yapılan güncellemeden beri ERP e-fatura modülü yanıt vermiyor.',
            'kok_neden' => 'Sürüm geçişi sonrası veritabanı sorgu kilitlenmesi (deadlock).',
            'operasyonel_risk' => 'Faturaların kesilememesi ve sevkiyatların durması.',
            'etiketler' => 'Canlı Üretim Kesintisi, Finansal / Fatura Etkisi, SLA Kritik Süre',
            'yonetici_onayi_sart' => true,
            'durum' => 'İnceleniyor (L2 Teknik Ekip)'
        ]);

        Ticket::create([
            'ticket_kodu' => 'TCK-1043',
            'calisan_ad_soyad' => 'Zeynep Kaya',
            'departman' => 'Bilgi Güvenliği / Yazılım',
            'kategori' => 'Ağ ve Firewall Yetki Talebi',
            'onem_seviyesi' => 'Uyarı',
            'ariza_bildirimi' => 'Dış tedarikçi API testi için 3306 portunun dış dünyaya açılması talebi.',
            'kok_neden' => 'Standart güvenlik protokollerini aşan kontrolsüz erişim isteği.',
            'operasyonel_risk' => 'Veritabanının dış ağa açılması sonucu yetkisiz erişim ve KVKK ihlali riski.',
            'etiketler' => 'KVKK / Veri Güvenliği Riski',
            'yonetici_onayi_sart' => true,
            'durum' => 'Bloke Edildi (Onay Bekliyor)'
        ]);

        Ticket::create([
            'ticket_kodu' => 'TCK-1044',
            'calisan_ad_soyad' => 'Murat Demir',
            'departman' => 'Sistem ve Altyapı',
            'kategori' => 'Sunucu Odası İklimlendirme',
            'onem_seviyesi' => 'Kritik',
            'ariza_bildirimi' => 'Merkez sistem odası kabin içi sıcaklık 34 dereceye yükseldi, veri-yok alarmı.',
            'kok_neden' => 'Birincil soğutucu kompresör arızası ve yedek klimanın devreye girmemesi.',
            'operasyonel_risk' => 'Fiziksel sunucularda aşırı ısınma kaynaklı donanım hasarı ve sistem çökmesi.',
            'etiketler' => 'Canlı Üretim Kesintisi, Donanım / Sensör Kaybı, SLA Kritik Süre',
            'yonetici_onayi_sart' => false,
            'durum' => 'Çözüldü'
        ]);
    }
}