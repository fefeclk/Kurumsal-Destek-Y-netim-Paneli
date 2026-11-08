# 🚀 Kurumsal IT Destek & Triyaj Paneli (AI Destekli SaaS)

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![OpenAI](https://img.shields.io/badge/OpenAI-412991?style=for-the-badge&logo=openai&logoColor=white)
![SQLite](https://img.shields.io/badge/sqlite-%2307405e.svg?style=for-the-badge&logo=sqlite&logoColor=white)

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
*   **Veritabanı:** SQLite (Dosya Tabanlı, Hızlı Kurulum)
*   **Entegrasyonlar:** OpenAI API, SMTP Mailer

## ⚙️ Kurulum (Plug & Play)

Proje, SQLite kullanılarak harici bir veritabanı sunucusuna ihtiyaç duymadan anında çalışacak şekilde (Plug & Play) tasarlanmıştır:

**1. Depoyu Klonlayın ve Klasöre Girin**
```bash
git clone [https://github.com/fefeclk/Kurumsal-Destek-Y-netim-Paneli.git](https://github.com/fefeclk/Kurumsal-Destek-Y-netim-Paneli.git)
cd Kurumsal-Destek-Y-netim-Paneli
2. Paketleri Yükleyin

composer install

3. Ayar Dosyasını (Environment) Hazırlayın.env.example dosyasının adını .env olarak değiştirin ve Mail ayarlarınızı girin. (Veritabanı ayarı varsayılan olarak SQLite kalmalıdır):

Kod snippet'i
DB_CONNECTION=sqlite

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=kendi.mailiniz@gmail.com
MAIL_PASSWORD=gmail_uygulama_sifresi
MAIL_ENCRYPTION=smtps
4. Kurulum İşlemlerini Tamamlayın
Aşağıdaki komutları sırasıyla çalıştırarak uygulama anahtarını üretin, veritabanını oluşturun ve tabloları kurun:

Bash
php artisan key:generate
php artisan migrate
5. Sunucuyu Başlatın

Bash
php artisan serve
Uygulama http://127.0.0.1:8000 adresinde kullanıma hazır olacaktır.

🧑‍💻 Varsayılan Giriş Bilgileri
IT Admin Paneli: /admin/login

Kullanıcı Adı: admin (veya .env dosyasındaki ADMIN_USER değeri)

Şifre: 123456 (veya .env dosyasındaki ADMIN_PASS değeri)

Geliştirici: Fadıl Efe Çelik • Yalova Üniversitesi Kurumsal Bilişim Uzmanlığı