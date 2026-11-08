<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_kodu',
        'calisan_ad_soyad',
        'departman',
        'kategori',
        'onem_seviyesi',
        'ariza_bildirimi',
        'kok_neden',
        'operasyonel_risk',
        'etiketler',
        'yonetici_onayi_sart',
        'durum',
    ];

    protected $casts = [
        'yonetici_onayi_sart' => 'boolean',
    ];
}