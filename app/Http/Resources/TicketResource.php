<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ticket_kodu' => $this->ticket_kodu,
            'talep_eden_calisan' => [
                'ad_soyad' => $this->calisan_ad_soyad,
                'bolum_departman' => $this->departman,
            ],
            'kategori' => $this->kategori,
            'onem_seviyesi' => $this->onem_seviyesi,
            'ariza_bildirimi' => $this->ariza_bildirimi,
            'nedensellik_analizi' => [
                'tespit_edilen_kok_neden' => $this->kok_neden,
                'hedef_sonuc_operasyonel_risk' => $this->operasyonel_risk,
            ],
            'kontrol_ve_etiketler' => [
                'secilen_etki_alanlari' => $this->etiketler ? array_map('trim', explode(',', $this->etiketler)) : [],
                'yonetici_onayi_zorunlu_mu' => (bool) $this->yonetici_onayi_sart,
            ],
            'mevcut_durum' => $this->durum,
            'olusturulma_tarihi' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}