# 🚀 Kurumsal IT Destek & Triyaj Paneli (AI Destekli SaaS)

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![OpenAI](https://img.shields.io/badge/OpenAI-412991?style=for-the-badge&logo=openai&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)

Kurumsal firmaların iç yazılım ve donanım destek süreçlerini (Helpdesk) otomatize etmek, yapay zeka ile önceliklendirmek ve OWASP standartlarında güvenli bir şekilde yönetmek amacıyla geliştirilmiş tam teşekküllü bir **Bilet (Ticket) Yönetim Sistemidir**.

## ✨ Öne Çıkan Özellikler

*   **🧠 Yapay Zeka (AI) Triyaj Modülü:** Çalışanların girdiği serbest arıza metinleri arka planda OpenAI API ile analiz edilir. Biletin kategorisi, önem derecesi (Kritik/Bilgi), muhtemel kök nedeni ve operasyonel riski sistem tarafından otomatik belirlenir.
*   **🛡️ Çift Faktörlü Doğrulama (2FA):** Yeni eklenen veya şifresini sıfırlayan kullanıcılar, sisteme ilk girişlerinde SMTP üzerinden e-postalarına gönderilen 6 haneli doğrulama kodunu girmek zorundadır.
*   **🔑 Self-Servis Şifre & Kullanıcı Yönetimi:**
    *   Admin yeni çalışan eklediğinde sistem 8 haneli rastgele güçlü bir şifre üretir.
    *   Çalışanlar şifrelerini unuttuğunda dışarıdan talep açar, admin panele "Şifre Sıfırlama Talebi" düşer.
    *   Admin tek tuşla talebi onaylar, sistem yeni şifreyi üretir ve bileti otomatik kapatır.
*   **📊 Dinamik Admin Dashboard:** Açılır/kapanır (Accordion) UI tasarımı, canlı istatistik kartları, L1/L2 atama modülleri ve `.env` üzerinden dinamik admin şifresi değiştirme altyapısı.
*   **🔒 Üst Düzey Güvenlik:** Sıkılaştırılmış Middleware yapısı, IDOR (Broken Access Control) koruması, CSRF kalkanı ve güvenli session yönetimi.

## 🛠️ Kullanılan Teknolojiler

*   **Backend:** PHP 8.x, Laravel 11.x
*   **Frontend:** Blade Templates, Tailwind CSS, Vanilla JavaScript
*   **Veritabanı:** MySQL
*   **Entegrasyonlar:** OpenAI API, SMTP Mailer

## ⚙️ Yerel Ortamda Kurulum (Local Development)

Projeyi kendi bilgisayarınızda çalıştırmak için aşağıdaki adımları izleyin:

**1. Depoyu Klonlayın**
```bash
git clone [https://github.com/fefeclk/Kurumsal-Destek-Y-netim-Paneli.git](https://github.com/fefeclk/Kurumsal-Destek-Y-netim-Paneli.git)
cd Kurumsal-Destek-Y-netim-Paneli