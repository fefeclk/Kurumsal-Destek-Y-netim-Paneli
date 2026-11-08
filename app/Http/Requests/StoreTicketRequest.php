<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Formu herkesin (çalışanların) gönderebilmesi için true yapıyoruz
        return true;
    }

    public function rules(): array
    {
        // Controller'daki o uzun kuralları buraya yapıştırıyoruz
        return [
            'calisan_ad_soyad' => 'required|string|max:100',
            'departman' => 'required|string|max:100',
            'ariza_bildirimi' => 'required|string|max:2000',
            'kategori' => 'nullable|string|max:100',
            'onem_seviyesi' => 'nullable|string|in:Kritik,Uyarı,Bilgi',
            'kok_neden' => 'nullable|string|max:500',
            'operasyonel_risk' => 'nullable|string|max:500',
            'etiketler' => 'nullable|array',
            'etiketler.*' => 'string|max:100',
            'yonetici_onayi_sart' => 'nullable|boolean',
        ];
    }
}