<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_kodu')->unique();
            $table->string('calisan_ad_soyad'); // Yeni eklendi: Talebi açan çalışan
            $table->string('departman');        // Çalışanın bölümü
            $table->string('kategori');
            $table->string('onem_seviyesi');
            $table->text('ariza_bildirimi');
            $table->text('kok_neden');
            $table->text('operasyonel_risk');
            $table->string('etiketler')->nullable();
            $table->boolean('yonetici_onayi_sart')->default(false);
            $table->string('durum')->default('Açık (L1 Triyaj)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};