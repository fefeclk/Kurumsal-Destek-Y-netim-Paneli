# 🏢 Kurumsal IT Destek ve Triyaj Yönetim Sistemi

Bu proje, Yalova Üniversitesi Kurumsal Bilişim Uzmanlığı vize projesi kapsamında geliştirilmiş; güvenlik sıkılaştırmaları (OWASP A04, A07 korumaları), Rol Tabanlı Erişim Kontrolü (RBAC) ve dinamik JavaScript UI filtrelemeleri içeren Laravel tabanlı bir IT Ticket yönetim sistemidir.

## 🚀 Kurulum Adımları (Release veya Kaynak Koddan)

Projeyi GitHub'daki **Releases** sekmesinden `Source code (zip)` olarak indirip klasöre çıkartın veya terminalden klonlayın:

git clone [https://github.com/fefeclk/Kurumsal-Destek-Y-netim-Paneli.git](https://github.com/fefeclk/Kurumsal-Destek-Y-netim-Paneli.git)
cd Kurumsal-Destek-Y-netim-Paneli

1. Paketleri Yükleyin
Laravel motorunun ve bağımlılıkların test sunucusuna indirilmesi için:

composer install

2. Ayar Dosyasını (Environment) Hazırlayın
Proje dizinindeki `.env.example` dosyasının adını `.env` olarak değiştirin. 
Şifre sıfırlama ve sistem bildirim e-postalarının çalışması için kendi SMTP (Mail) ayarlarınızı girin. *(Gmail, Outlook, Yandex veya kendi kurumsal sunucunuzu kullanabilirsiniz. Sistem varsayılan veritabanı olarak SQLite kullanmaktadır, DB ayarı değiştirilmemelidir)*:

env
DB_CONNECTION=sqlite

MAIL_MAILER=smtp
MAIL_HOST=mail_sunucunuz     # Örn: smtp.gmail.com, smtp.office365.com veya mail.sirketiniz.com
MAIL_PORT=587                # SMTP Portunuz (Genelde 587, 465 veya 2525)
MAIL_USERNAME=kendi_mailiniz # Gönderici e-posta adresi
MAIL_PASSWORD=mail_sifreniz  # E-posta şifreniz (Gmail için "Uygulama Şifresi" gerektirir)
MAIL_ENCRYPTION=tls          # Şifreleme türü (tls, ssl veya smtps)

3. Kurulum İşlemlerini Tamamlayın
Aşağıdaki komutları sırasıyla çalıştırarak uygulama güvenlik anahtarını üretin ve veritabanı tablolarını kurun:

php artisan key:generate
php artisan migrate
(Not: Migration sırasında "database.sqlite veritabanı oluşturulsun mu?" uyarısı çıkarsa "Yes" diyerek onaylayın).

4. Sunucuyu Başlatın
php artisan serve
Uygulama http://127.0.0.1:8000 adresinde yerel sunucuda kullanıma hazır olacaktır.

🧑‍💻 Varsayılan Giriş Bilgileri (Acil Durum Süper Admin)
Sıfır kurulumlarda sistemi başlatabilmeniz için bir "Süper Admin" arka kapısı oluşturulmuştur:

Ortak Giriş Adresi: /login

Kurumsal E-Posta: admin@admin.com

Şifre: admin

⚠️ Güvenlik Notu (OWASP Hardening): Sistemde yetkilendirilmiş gerçek bir Admin hesabı oluşturulduğu an, bu varsayılan admin@admin.com hesabıyla giriş yapılması sistem tarafından güvenlik amacıyla otomatik olarak engellenecektir.

👨‍💻 Geliştirici: Fadıl Efe Çelik 