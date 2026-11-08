<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Rehberi & SSS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
    <style>
        html.light-mode body { background-color: #f1f5f9 !important; color: #0f172a !important; }
        html.light-mode .bg-slate-800 { background-color: #ffffff !important; border-color: #cbd5e1 !important; color: #0f172a !important; }
        html.light-mode .bg-slate-900, html.light-mode .bg-slate-900\/50 { background-color: #e2e8f0 !important; color: #0f172a !important; }
        html.light-mode .text-slate-100, html.light-mode .text-white { color: #0f172a !important; }
        html.light-mode .text-slate-200, html.light-mode .text-slate-300 { color: #334155 !important; }
        html.light-mode .text-slate-400 { color: #475569 !important; }
        html.light-mode .border-slate-700 { border-color: #cbd5e1 !important; }
        
        html.light-mode :not(button):not(a).text-slate-100, html.light-mode :not(button):not(a).text-white { color: #0f172a !important; }
        html.light-mode button.bg-slate-700 { background-color: #f1f5f9 !important; color: #0f172a !important; border: 1px solid #cbd5e1; }
        html.light-mode a.bg-blue-600 { color: #ffffff !important; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen p-6 transition-colors duration-300">
    <div class="max-w-5xl mx-auto space-y-6">
        
        <!-- Üst Başlık Barı -->
        <div class="bg-slate-800 p-5 rounded-2xl border border-slate-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 shadow-lg">
            <div>
                <span class="bg-indigo-600/20 text-indigo-400 border border-indigo-500/40 text-xs font-mono px-2.5 py-1 rounded">DOKÜMANTASYON</span>
                <h1 class="text-2xl font-bold text-white mt-2">❓ Sıkça Sorulan Sorular & Sistem Rehberi</h1>
                <p class="text-xs text-slate-400 mt-1">Sistemin işleyişi, yapay zeka modülü ve güvenlik prosedürleri hakkında bilgiler.</p>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.panel') }}" class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition shadow">
                    ← Panele Dön
                </a>
                <button type="button" onclick="temaDegistir()" id="tema_btn" class="bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs font-semibold px-3.5 py-2.5 rounded-xl transition flex items-center gap-1.5">
                    <span id="tema_ikon">☀️</span>
                </button>
            </div>
        </div>

        <!-- Bilgi Kartları (Grid Yapısı) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-md">
                <h2 class="text-lg font-bold text-blue-400 mb-3 flex items-center gap-2"><span>👤</span> Yeni Çalışan Nasıl Eklenir?</h2>
                <p class="text-sm text-slate-300 leading-relaxed mb-3">
                    Ana paneldeki "Personel ve Hesap Yönetimi" sekmesinden yeni çalışan ekleyebilirsiniz. Şifre girmekle uğraşmazsınız; sistem otomatik olarak <b class="text-white">8 haneli güçlü bir şifre</b> üretir ve ekranda size gösterir. Bu şifreyi çalışana iletmeniz yeterlidir. Çalışan daha sonra kendi profilinden şifresini değiştirebilir.
                </p>
            </div>

            <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-md">
                <h2 class="text-lg font-bold text-purple-400 mb-3 flex items-center gap-2"><span>🛡️</span> 2FA (Çift Faktörlü Doğrulama) Nedir?</h2>
                <p class="text-sm text-slate-300 leading-relaxed mb-3">
                    Sistemimiz ekstra güvenlik katmanıyla (OWASP standartlarında) korunmaktadır. Sisteme eklediğiniz her yeni çalışan, ilk girişinde e-postasına giden <b class="text-white">6 haneli doğrulama kodunu</b> girmek zorundadır. Bu doğrulama yapılmadan destek talebi oluşturulamaz.
                </p>
            </div>

            <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-md">
                <h2 class="text-lg font-bold text-teal-400 mb-3 flex items-center gap-2"><span>✨</span> Yapay Zeka (AI) Triyajı Nasıl Çalışır?</h2>
                <p class="text-sm text-slate-300 leading-relaxed mb-3">
                    Çalışanlar sorunu serbest metin olarak yazarlar. Form gönderilirken arkaplanda çalışan <b class="text-white">OpenAI Entegrasyonu</b> bu metni analiz eder. Kategori (Örn: Donanım, Yazılım), Önem Seviyesi (Kritik, Bilgi) ve Olası Kök Nedeni otomatik belirleyerek IT ekibinin iş yükünü %70 oranında azaltır.
                </p>
            </div>

            <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-md">
                <h2 class="text-lg font-bold text-amber-400 mb-3 flex items-center gap-2"><span>🏷️</span> Ticket Durumları Ne Anlama Geliyor?</h2>
                <ul class="text-sm text-slate-300 space-y-2 mt-2">
                    <li><strong class="text-white bg-slate-900/50 px-2 py-0.5 rounded border border-slate-700">Açık (L1 Triyaj):</strong> Sisteme yeni düşmüş ve henüz müdahale edilmemiş kayıtlar.</li>
                    <li><strong class="text-white bg-slate-900/50 px-2 py-0.5 rounded border border-slate-700">İnceleniyor (L2 Teknik Ekip):</strong> Uzman bir IT personelinin üzerinde çalıştığı kayıtlar.</li>
                    <li><strong class="text-white bg-slate-900/50 px-2 py-0.5 rounded border border-slate-700">Bloke Edildi:</strong> Riskli görülen veya yönetici (müdür) onayı bekleyen talepler.</li>
                    <li><strong class="text-white bg-slate-900/50 px-2 py-0.5 rounded border border-slate-700">Çözüldü:</strong> Başarıyla sonlandırılmış teknik destek işlemleri.</li>
                </ul>
            </div>
        </div>

    </div>

    <script>
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.add('light-mode');
            if(document.getElementById('tema_ikon')) document.getElementById('tema_ikon').innerText = '🌙';
        }
        function temaDegistir() {
            const html = document.documentElement;
            const ikon = document.getElementById('tema_ikon');
            if (html.classList.contains('light-mode')) {
                html.classList.remove('light-mode');
                localStorage.setItem('theme', 'dark');
                if(ikon) ikon.innerText = '☀️';
            } else {
                html.classList.add('light-mode');
                localStorage.setItem('theme', 'light');
                if(ikon) ikon.innerText = '🌙';
            }
        }
    </script>
</body>
</html>