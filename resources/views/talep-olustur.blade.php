<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Çalışan Destek Bildirimi - Kurumsal Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
    <style>
        /* Açık Tema Arka Plan ve Çerçeve Geçişleri */
        html.light-mode body { background-color: #f1f5f9 !important; color: #0f172a !important; }
        html.light-mode .bg-slate-800, html.light-mode .bg-slate-800\/70 { background-color: #ffffff !important; border-color: #cbd5e1 !important; color: #0f172a !important; }
        html.light-mode .bg-slate-900, html.light-mode .bg-slate-900\/50, html.light-mode .bg-slate-900\/90, html.light-mode .bg-slate-900\/60, html.light-mode .bg-slate-950, html.light-mode .bg-slate-950\/80 { background-color: #e2e8f0 !important; color: #0f172a !important; }
        html.light-mode .bg-amber-950\/30 { background-color: #fef3c7 !important; border-color: #fde68a !important; }
        html.light-mode .border-slate-700, html.light-mode .border-slate-700\/50 { border-color: #cbd5e1 !important; }
        
        /* 🚨 SADECE KART İÇİ YAZILARI DÜZELT, BUTONLARA DOKUNMA 🚨 */
        html.light-mode :not(button):not(a).text-slate-100, 
        html.light-mode :not(button):not(a).text-white { color: #0f172a !important; }
        html.light-mode :not(button):not(a).text-slate-200, 
        html.light-mode :not(button):not(a).text-slate-300 { color: #334155 !important; }
        html.light-mode :not(button):not(a).text-slate-400 { color: #475569 !important; }
        
        /* Açık temada sarı tonlarını okunabilir koyu turuncuya çevir */
        html.light-mode .text-amber-400, html.light-mode .text-amber-500 { color: #b45309 !important; }

        /* 🛠️ BUTONLAR İÇİN ÖZEL AÇIK TEMA KURALLARI 🛠️ */
        html.light-mode button.bg-slate-700 { background-color: #f1f5f9 !important; color: #0f172a !important; border: 1px solid #cbd5e1; }
        html.light-mode button.bg-slate-700:hover { background-color: #e2e8f0 !important; }
        html.light-mode button.bg-red-900\/80 { background-color: #ef4444 !important; color: #ffffff !important; border-color: #dc2626 !important; }
        html.light-mode a.bg-blue-600, html.light-mode a.bg-teal-600, html.light-mode button.bg-blue-600 { color: #ffffff !important; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen p-6 flex flex-col justify-between transition-colors duration-300 relative">
    <div class="max-w-3xl mx-auto w-full space-y-6">
        
        {{-- Üst Başlık Barı ve Aksiyon Butonları --}}
        <div class="bg-slate-800 p-5 rounded-2xl border border-slate-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 shadow-lg">
            <div>
                <span class="bg-blue-600/20 text-blue-400 border border-blue-500/40 text-xs font-mono px-2.5 py-1 rounded">KURUMSAL İÇ PORTAL</span>
                <h1 class="text-2xl font-bold text-white mt-1.5">Arıza ve Destek (Ticket) Bildirim Formu</h1>
                <p class="text-xs text-slate-400">Sorununuzu bildirin; sistem otomatik önceliklendirme ile ilgili teknik ekibe yönlendirecektir.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                
                {{-- Şifre Değiştirme Butonu (EKSİKTİ, EKLENDİ) --}}
                <button type="button" onclick="document.getElementById('sifre_modal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-500 text-white text-xs px-3 py-1.5 rounded-lg transition shadow">
                    🔑 Şifre Değiştir
                </button>

                <!-- Çalışan Çıkış Butonu -->
                <form action="{{ route('calisan.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-900/80 hover:bg-red-700 text-white text-xs px-3 py-1.5 rounded-lg transition border border-red-700">🚪 Çıkış</button>
                </form>
                
                <!-- Tema Butonu -->
                <button type="button" onclick="temaDegistir()" class="bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs px-3 py-1.5 rounded-lg transition" title="Tema Değiştir">
                    <span id="tema_ikon">☀️</span>
                </button>
                
                <span class="bg-emerald-950/80 text-emerald-400 border border-emerald-700/50 text-xs font-mono px-3 py-1.5 rounded-lg shrink-0">
                    ● Sistem Aktif
                </span>
            </div>
        </div>

        <!-- Bildirim Kutusu -->
        @if(session('basari'))
            <div id="bildirim_kutusu" class="bg-emerald-950 border border-emerald-600 text-emerald-300 px-5 py-4 rounded-xl text-sm flex justify-between items-center shadow-lg">
                <span>{{ session('basari') }}</span>
                <button type="button" onclick="document.getElementById('bildirim_kutusu').remove()" class="text-emerald-400 hover:text-white font-bold ml-4 text-base">
                    ✕
                </button>
            </div>
        @endif

        {{-- Ana Ticket Oluşturma Formu --}}
        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-xl">
            <form id="ticket_form" action="{{ route('web.ticket.store') }}" method="POST" class="space-y-6 text-sm">
                @csrf

                <!-- AI Tarafından Otomatik Doldurulacak Gizli Alanlar -->
                <input type="hidden" id="kategori" name="kategori" value="">
                <input type="hidden" id="onem_seviyesi" name="onem_seviyesi" value="">
                <input type="hidden" id="kok_neden" name="kok_neden" value="">
                <input type="hidden" id="operasyonel_risk" name="operasyonel_risk" value="">

                {{-- Güvenlik: İsim artık arka planda gizlice gidiyor --}}
                <input type="hidden" name="calisan_ad_soyad" value="{{ auth()->user()->name }}">

                {{-- 1. Bölüm: Çalışan Kimlik ve Departman Bilgileri --}}
                <div class="border-b border-slate-700 pb-5">
                    <h2 class="text-sm font-bold text-blue-400 uppercase tracking-wider mb-3">1. Bildirimi Yapan Çalışan Bilgileri</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                        <div>
                            <!-- GEREKSİZ İNPUT SİLİNDİ, YERİNE SABİT VE ŞIK BİR KUTU GELDİ -->
                            <label class="text-xs text-slate-400 font-semibold block mb-1.5">Sistem Kullanıcısı</label>
                            <div class="w-full bg-slate-900/50 border border-slate-700/50 rounded-xl p-3 text-slate-300 font-mono text-sm">
                                👤 {{ auth()->user()->name }} <span class="text-xs text-emerald-500 ml-2">(Sistemden Alındı)</span>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs text-slate-300 font-semibold block mb-1.5">Bölüm / Departman *</label>
                            <select id="departman" name="departman" required class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white focus:border-blue-500 focus:outline-none">
                                <option value="">-- Bölümünüzü Seçin --</option>
                                <option value="Finans ve Muhasebe">Finans ve Muhasebe</option>
                                <option value="İnsan Kaynakları">İnsan Kaynakları</option>
                                <option value="Lojistik ve Depo Operasyon">Lojistik ve Depo Operasyon</option>
                                <option value="Satış ve Pazarlama">Satış ve Pazarlama</option>
                                <option value="Bilgi Güvenliği / Yazılım">Bilgi Güvenliği / Yazılım</option>
                                <option value="Sistem ve Altyapı">Sistem ve Altyapı</option>
                                <option value="Üretim ve Kalite">Üretim ve Kalite</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- 2. Bölüm: Arıza Açıklaması --}}
                <div class="border-b border-slate-700 pb-5 space-y-3">
                    <div class="flex justify-between items-center">
                        <h2 class="text-sm font-bold text-blue-400 uppercase tracking-wider">2. Yaşadığınız Sorun veya Talep</h2>
                        <span class="text-xs bg-purple-900/50 text-purple-300 border border-purple-700/60 px-2.5 py-1 rounded-full font-mono">
                            ✨ Otomatik AI Triyaj Destekli
                        </span>
                    </div>
                    <div>
                        <label class="text-xs text-slate-300 font-semibold block mb-1.5">Sorunu Kısaca Anlatın *</label>
                        <textarea id="ariza_bildirimi" name="ariza_bildirimi" rows="4" required placeholder="Örn: Sabah yapılan güncellemeden beri e-fatura kesemiyoruz, ekran donuyor..." class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3.5 text-white focus:border-blue-500 focus:outline-none"></textarea>
                    </div>
                </div>

                {{-- 3. Bölüm: Eksiksiz Checkbox'lar --}}
                <div class="space-y-4">
                    <h2 class="text-sm font-bold text-blue-400 uppercase tracking-wider">3. Gözlemlenen Durumlar (Checkbox Seçimi)</h2>
                    
                    <div class="bg-slate-900/90 p-4 rounded-xl border border-slate-700 space-y-2.5">
                        <label class="text-xs text-slate-300 font-semibold block">Size uygun olan durumları işaretleyin (İsteğe bağlı / Çoklu seçim):</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-xs text-slate-300 pt-1">
                            <label class="flex items-center gap-2 cursor-pointer bg-slate-800/70 p-2.5 rounded-lg border border-slate-700 hover:border-blue-500">
                                <input type="checkbox" name="etiketler[]" value="Canlı Üretim Kesintisi" class="chk-etiket w-4 h-4 rounded bg-slate-900 border-slate-600 text-blue-500">
                                <span>🔥 İş / Üretim Durdu</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer bg-slate-800/70 p-2.5 rounded-lg border border-slate-700 hover:border-blue-500">
                                <input type="checkbox" name="etiketler[]" value="KVKK / Veri Güvenliği Riski" class="chk-etiket w-4 h-4 rounded bg-slate-900 border-slate-600 text-blue-500">
                                <span>🛡️ Yetki / Güvenlik Talebi</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer bg-slate-800/70 p-2.5 rounded-lg border border-slate-700 hover:border-blue-500">
                                <input type="checkbox" name="etiketler[]" value="SLA Kritik Süre" class="chk-etiket w-4 h-4 rounded bg-slate-900 border-slate-600 text-blue-500">
                                <span>⏱️ Acil Yetişmesi Gerekiyor</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer bg-slate-800/70 p-2.5 rounded-lg border border-slate-700 hover:border-blue-500">
                                <input type="checkbox" name="etiketler[]" value="Finansal / Fatura Etkisi" class="chk-etiket w-4 h-4 rounded bg-slate-900 border-slate-600 text-blue-500">
                                <span>💳 Fatura / Ödeme Etkisi</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer bg-slate-800/70 p-2.5 rounded-lg border border-slate-700 hover:border-blue-500">
                                <input type="checkbox" name="etiketler[]" value="Donanım / Sensör Kaybı" class="chk-etiket w-4 h-4 rounded bg-slate-900 border-slate-600 text-blue-500">
                                <span>📡 Cihaz / Donanım Arızası</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer bg-slate-800/70 p-2.5 rounded-lg border border-slate-700 hover:border-blue-500">
                                <input type="checkbox" name="etiketler[]" value="Veritabanı / Sunucu Yükü" class="chk-etiket w-4 h-4 rounded bg-slate-900 border-slate-600 text-blue-500">
                                <span>🗄️ Sistem Çok Yavaş</span>
                            </label>
                        </div>
                    </div>

                    <div class="bg-amber-950/30 border border-amber-700/60 p-3.5 rounded-xl">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" id="yonetici_onayi_sart" name="yonetici_onayi_sart" value="1" class="mt-0.5 w-4 h-4 rounded bg-slate-900 border-amber-500 text-amber-500">
                            <div class="text-xs">
                                <strong class="text-amber-400 block">🔒 Sistem Değişikliği / Ekstra Yetki Talebi İçeriyor</strong>
                                <span class="text-slate-400">Port açma, dış erişim veya sistem yetkisi istiyorsanız işaretleyin (Yönetici onayına sunulur).</span>
                            </div>
                        </label>
                    </div>
                </div>

                <button type="submit" id="gonder_btn" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-blue-900/40 text-sm flex justify-center items-center gap-2">
                    <span>Destek Talebini Gönder →</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Alt Bilgi ve Admin Giriş Linki --}}
    <footer class="max-w-3xl mx-auto w-full mt-8 pt-4 border-t border-slate-800 flex justify-between items-center text-xs text-slate-500">
        <span>Kurumsal Bilişim Destek Portalı</span>
        <a href="{{ route('admin.login') }}" class="hover:text-slate-300 transition">🔒 IT Personel / Admin Girişi</a>
    </footer>

    {{-- GİZLİ ŞİFRE DEĞİŞTİRME PENCERESİ (MODAL) (EKSİKTİ, EKLENDİ) --}}
    <div id="sifre_modal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex justify-center items-center z-50 p-4">
        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 w-full max-w-sm shadow-2xl relative">
            <button type="button" onclick="document.getElementById('sifre_modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-white font-bold text-lg">✕</button>
            <h3 class="text-lg font-bold text-white mb-2">🔑 Şifrenizi Yenileyin</h3>
            <p class="text-xs text-slate-400 mb-5">Admin tarafından verilen geçici şifrenizi buradan değiştirebilirsiniz.</p>
            
            <form action="{{ route('calisan.sifre.degistir') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-xs text-slate-300 block mb-1">Yeni Şifreniz (En az 6 karakter)</label>
                    <input type="password" name="yeni_sifre" required minlength="6" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white focus:border-blue-500 focus:outline-none">
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-xl shadow-lg transition text-sm">Şifreyi Güncelle</button>
            </form>
        </div>
    </div>

    {{-- Scriptler: Tema ve AI Form Gönderimi --}}
    <script>
        // Tema Ayarı
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

        // AI Triyaj Form Gönderimi
        const form = document.getElementById('ticket_form');
        let aiTamamlandi = false;

        form.addEventListener('submit', async function(e) {
            if (aiTamamlandi) return;
            e.preventDefault();

            const metin = document.getElementById('ariza_bildirimi').value.trim();
            const departman = document.getElementById('departman').value;
            const btn = document.getElementById('gonder_btn');

            btn.disabled = true;
            btn.innerHTML = `<span class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></span> <span>Talebiniz İletiliyor...</span>`;

            const prompt = `Sen kurumsal bir IT destek yapay zekasısın. Çalışanın (${departman} departmanı) girdiği arıza metnini analiz et ve SADECE şu JSON formatında yanıt ver:
            {
                "kategori": "Kısa teknik arıza kategorisi (Örn: ERP Veritabanı Hatası, Ağ Güvenliği vb.)",
                "onem_seviyesi": "Kritik veya Uyarı veya Bilgi",
                "kok_neden": "Muhtemel teknik kök neden (Nedensellik analizi)",
                "operasyonel_risk": "Çözülmezse oluşacak kurumsal/operasyonel risk (Hedef-Sonuç)",
                "secilen_etiketler": ["Canlı Üretim Kesintisi", "KVKK / Veri Güvenliği Riski", "SLA Kritik Süre", "Finansal / Fatura Etkisi", "Donanım / Sensör Kaybı", "Veritabanı / Sunucu Yükü" içinden uygun olanlar],
                "yonetici_onayi_sart": true veya false
            }`;

            try {
                const res = await fetch('https://text.pollinations.ai/openai', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        model: 'openai',
                        response_format: { type: 'json_object' },
                        messages: [
                            { role: 'system', content: prompt },
                            { role: 'user', content: metin }
                        ]
                    })
                });
                const data = await res.json();
                const clean = data.choices[0].message.content.replace(/^```json\s*|\s*```$/gi, '').trim();
                const sonuc = JSON.parse(clean);

                document.getElementById('kategori').value = sonuc.kategori || '';
                document.getElementById('onem_seviyesi').value = ['Kritik', 'Uyarı', 'Bilgi'].includes(sonuc.onem_seviyesi) ? sonuc.onem_seviyesi : '';
                document.getElementById('kok_neden').value = sonuc.kok_neden || '';
                document.getElementById('operasyonel_risk').value = sonuc.operasyonel_risk || '';

                if (sonuc.yonetici_onayi_sart) {
                    document.getElementById('yonetici_onayi_sart').checked = true;
                }

                if (Array.isArray(sonuc.secilen_etiketler)) {
                    document.querySelectorAll('.chk-etiket').forEach(chk => {
                        if (sonuc.secilen_etiketler.includes(chk.value)) {
                            chk.checked = true;
                        }
                    });
                }
            } catch (err) {}

            aiTamamlandi = true;
            form.submit();
        });
    </script>
</body>
</html>