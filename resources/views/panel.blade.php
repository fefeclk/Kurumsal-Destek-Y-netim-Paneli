<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Admin Paneli & Kurumsal Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
    <style>
        html.light-mode body { background-color: #f1f5f9 !important; color: #0f172a !important; }
        html.light-mode .bg-slate-800, html.light-mode .bg-slate-800\/40, html.light-mode .bg-slate-800\/70 { background-color: #ffffff !important; border-color: #cbd5e1 !important; color: #0f172a !important; }
        html.light-mode .bg-slate-900, html.light-mode .bg-slate-900\/50, html.light-mode .bg-slate-900\/60, html.light-mode .bg-slate-950 { background-color: #e2e8f0 !important; color: #0f172a !important; }
        html.light-mode .bg-amber-950\/30 { background-color: #fef3c7 !important; border-color: #fde68a !important; }
        html.light-mode .border-slate-700, html.light-mode .border-slate-700\/80, html.light-mode .border-slate-700\/50 { border-color: #cbd5e1 !important; }
        html.light-mode :not(button):not(a).text-slate-100, html.light-mode :not(button):not(a).text-white { color: #0f172a !important; }
        html.light-mode :not(button):not(a).text-slate-200, html.light-mode :not(button):not(a).text-slate-300 { color: #334155 !important; }
        html.light-mode :not(button):not(a).text-slate-400 { color: #475569 !important; }
        html.light-mode .text-amber-400, html.light-mode .text-amber-500 { color: #b45309 !important; }
        html.light-mode button.bg-slate-700 { background-color: #f1f5f9 !important; color: #0f172a !important; border: 1px solid #cbd5e1; }
        html.light-mode button.bg-slate-700:hover { background-color: #e2e8f0 !important; }
        html.light-mode button.bg-red-900\/80 { background-color: #ef4444 !important; color: #ffffff !important; border-color: #dc2626 !important; }
        html.light-mode a.bg-blue-600, html.light-mode a.bg-teal-600, html.light-mode button.bg-blue-600 { color: #ffffff !important; }
        html.light-mode .bg-amber-600 { background-color: #d97706 !important; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen p-6 transition-colors duration-300">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <div class="bg-slate-800 p-5 rounded-2xl border border-slate-700 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 shadow-lg">
            <div>
                <div class="flex items-center gap-2">
                    <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 text-xs font-mono px-2.5 py-1 rounded">IT ADMIN & TRİYAJ MERKEZİ</span>
                    <span class="bg-red-500/20 text-red-400 border border-red-500/40 text-xs font-mono px-2.5 py-1 rounded">KB 206 • LARAVEL API</span>
                </div>
                <h1 class="text-2xl font-bold text-white mt-2">Kurumsal Destek Yönetim Paneli</h1>
                <p class="text-xs text-slate-400 mt-1">API Endpoint: /api/v1/external/tickets (Key: kurumsal-2026-secret)</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2">
                {{-- YENİ: Admin Şifre Değiştirme Butonu --}}
                <button type="button" onclick="document.getElementById('admin_sifre_modal').classList.remove('hidden')" class="bg-amber-600 hover:bg-amber-500 text-white text-xs font-semibold px-3.5 py-2.5 rounded-xl transition flex items-center gap-1.5 shadow">
                    🔑 Admin Şifresi
                </button>
                <a href="{{ route('admin.sss') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-3.5 py-2.5 rounded-xl transition flex items-center gap-1.5 shadow">
                    ❓ SSS / Rehber
                </a>
                <button type="button" onclick="temaDegistir()" id="tema_btn" class="bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs font-semibold px-3.5 py-2.5 rounded-xl transition flex items-center gap-1.5">
                    <span id="tema_ikon">☀️</span> <span id="tema_yazı">Açık Tema</span>
                </button>
                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-900/80 hover:bg-red-700 text-red-100 border border-red-700 text-xs font-semibold px-3.5 py-2.5 rounded-xl transition">
                        🚪 Çıkış Yap
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-slate-800 p-4 rounded-xl border border-slate-700 flex justify-between items-center shadow">
                <div><span class="text-xs text-slate-400 block">Sisteme Düşen Ticket</span><span class="text-2xl font-bold text-white">{{ $tickets->count() }}</span></div>
                <span class="text-2xl bg-blue-900/40 p-2.5 rounded-lg border border-blue-700/50">📊</span>
            </div>
            <div class="bg-slate-800 p-4 rounded-xl border border-red-900/60 flex justify-between items-center shadow">
                <div><span class="text-xs text-red-400 block">Kritik Vakalar</span><span class="text-2xl font-bold text-red-400">{{ $tickets->where('onem_seviyesi', 'Kritik')->count() }}</span></div>
                <span class="text-2xl bg-red-900/40 p-2.5 rounded-lg border border-red-700/50">🚨</span>
            </div>
            <div class="bg-slate-800 p-4 rounded-xl border border-amber-900/60 flex justify-between items-center shadow">
                <div><span class="text-xs text-amber-400 block">Onay Bekleyen</span><span class="text-2xl font-bold text-amber-400">{{ $tickets->where('yonetici_onayi_sart', true)->count() }}</span></div>
                <span class="text-2xl bg-amber-900/40 p-2.5 rounded-lg border border-amber-700/50">🔒</span>
            </div>
            <div class="bg-slate-800 p-4 rounded-xl border border-emerald-900/60 flex justify-between items-center shadow">
                <div><span class="text-xs text-emerald-400 block">Çözülen Kayıtlar</span><span class="text-2xl font-bold text-emerald-400">{{ $tickets->where('durum', 'Çözüldü')->count() }}</span></div>
                <span class="text-2xl bg-emerald-900/40 p-2.5 rounded-lg border border-emerald-700/50">✅</span>
            </div>
        </div>

        @if(session('basari'))
            <div class="bg-emerald-950 border border-emerald-600 text-emerald-300 px-4 py-3 rounded-xl text-xs font-semibold shadow">✅ {{ session('basari') }}</div>
        @endif
        @if($errors->any())
            <div class="bg-red-950 border border-red-600 text-red-300 px-4 py-3 rounded-xl text-xs font-semibold shadow space-y-1">
                @foreach($errors->all() as $error) <div>⚠️ {{ $error }}</div> @endforeach
            </div>
        @endif

        <div class="bg-slate-800 rounded-2xl border border-slate-700 shadow-md overflow-hidden">
            <div onclick="toggleAccordion('personel_yonetimi')" class="p-4 cursor-pointer flex justify-between items-center hover:bg-slate-700/50 transition select-none">
                <div class="flex items-center gap-3">
                    <span class="text-xl">👥</span>
                    <h2 class="text-sm font-bold text-blue-400 uppercase tracking-wider">Personel ve Hesap Yönetimi</h2>
                    <span class="bg-blue-900/60 text-blue-200 border border-blue-700 text-[10px] px-2.5 py-1 rounded-full font-bold">{{ $calisanlar->count() }} Kayıtlı Personel</span>
                </div>
                <span id="ok-personel_yonetimi" class="text-slate-400 transform transition-transform duration-300 text-lg">▼</span>
            </div>

            <div id="detay-personel_yonetimi" class="hidden p-5 border-t border-slate-700/80 bg-slate-800/40 space-y-6">
                <div class="bg-slate-900/50 p-4 rounded-xl border border-slate-700/50">
                    <h3 class="text-xs font-bold text-slate-400 mb-3 uppercase tracking-wider">Yeni Çalışan Ekle</h3>
                    <form action="{{ route('admin.calisan.ekle') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        @csrf
                        <div>
                            <label class="text-xs text-slate-400 font-semibold block mb-1.5">Ad Soyad</label>
                            <input type="text" name="name" required class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-xs text-slate-400 font-semibold block mb-1.5">Kurumsal E-posta</label>
                            <input type="email" name="email" required class="w-full bg-slate-900 border border-slate-700 rounded-lg p-2.5 text-white text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 px-4 rounded-lg shadow-lg transition text-sm">
                            Kaydet (Oto-Şifre Üret) ⚡
                        </button>
                    </form>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-slate-400 mb-3 uppercase tracking-wider">Sistemdeki Personeller</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($calisanlar as $c)
                            <div class="bg-slate-900/60 p-4 rounded-xl border border-slate-700 flex flex-col justify-between gap-3 shadow-sm">
                                <div>
                                    <div class="font-bold text-white text-sm">👤 {{ $c->name }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">📧 {{ $c->email }}</div>
                                </div>
                                <div class="flex justify-between items-center border-t border-slate-700/50 pt-3">
                                    @if($c->email_verified_at)
                                        <span class="bg-emerald-900/60 text-emerald-300 border border-emerald-700 text-[10px] px-2 py-1 rounded-full font-bold">✅ 2FA Aktif</span>
                                    @else
                                        <span class="bg-red-900/60 text-red-300 border border-red-700 text-[10px] px-2 py-1 rounded-full font-bold">❌ 2FA Bekliyor</span>
                                    @endif

                                    <div class="flex gap-2">
                                        <form action="{{ route('admin.calisan.sifirla', $c->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" title="Manuel Şifre Sıfırla" class="text-[10px] text-amber-400 hover:text-white bg-amber-950/30 hover:bg-amber-600 border border-amber-900/50 px-2 py-1 rounded transition">
                                                Sıfırla
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.calisan.sil', $c->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-[10px] text-red-400 hover:text-white bg-red-950/30 hover:bg-red-600 border border-red-900/50 px-2 py-1 rounded transition">
                                                Sil
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 mt-8 mb-2">
            <span class="text-lg">🎫</span> <h2 class="text-sm font-bold text-blue-400 uppercase tracking-wider">Gelen Destek Talepleri (Tickets)</h2>
        </div>

        <div class="space-y-4">
            @foreach($tickets as $t)
                <div class="bg-slate-800 rounded-2xl border border-slate-700 shadow-md overflow-hidden">
                    <div onclick="toggleAccordion('ticket_{{ $t->id }}')" class="p-4 cursor-pointer flex flex-wrap justify-between items-center gap-3 hover:bg-slate-700/50 transition select-none">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="font-mono text-xs bg-slate-900 border border-slate-700 px-3 py-1 rounded-lg text-blue-400 font-bold">{{ $t->ticket_kodu }}</span>
                            <span class="text-sm font-bold text-white">👤 {{ $t->calisan_ad_soyad }}</span>
                            
                            @if($t->kategori === 'Şifre Sıfırlama Talebi')
                                <span class="text-xs px-3 py-1 rounded-full font-semibold bg-amber-900/60 text-amber-300 border border-amber-700 animate-pulse">
                                    🚨 ŞİFRE SIFIRLAMA TALEBİ
                                </span>
                            @else
                                <span class="text-xs px-3 py-1 rounded-full font-semibold {{ $t->durum === 'Çözüldü' ? 'bg-emerald-900/60 text-emerald-300 border border-emerald-700' : 'bg-blue-900/60 text-blue-200 border border-blue-700' }}">
                                    {{ $t->durum }}
                                </span>
                                <span class="text-xs px-3 py-1 rounded-full font-semibold {{ $t->onem_seviyesi === 'Kritik' ? 'bg-red-900/60 text-red-200 border border-red-700' : 'bg-amber-900/60 text-amber-200 border border-amber-700' }}">
                                    {{ $t->onem_seviyesi }}
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-slate-400 font-mono">{{ $t->created_at?->format('H:i') }}</span>
                            <span id="ok-ticket_{{ $t->id }}" class="text-slate-400 transform transition-transform duration-300 text-lg">▼</span>
                        </div>
                    </div>

                    <div id="detay-ticket_{{ $t->id }}" class="hidden p-5 border-t border-slate-700/80 space-y-3 bg-slate-800/40">
                        <div class="text-xs text-slate-400 mb-1">🏢 {{ $t->departman }} • 🏷️ {{ $t->kategori }}</div>
                        <p class="text-sm text-slate-200 bg-slate-900/60 p-3.5 rounded-xl border border-slate-700/50">"{{ $t->ariza_bildirimi }}"</p>

                        @if($t->kategori === 'Şifre Sıfırlama Talebi')
                            <div class="flex flex-wrap justify-between items-center pt-3 border-t border-slate-700/60 mt-3">
                                <form action="{{ route('admin.sifre.oto', $t->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-500 text-white py-3 rounded-xl transition font-bold text-sm shadow-lg flex justify-center items-center gap-2">
                                        🔑 Otomatik Şifre Üret ve Talebi Kapat
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                @if($t->etiketler)
                                    @foreach(explode(',', $t->etiketler) as $etiket)
                                        <span class="text-xs bg-slate-900 text-teal-300 border border-teal-800/60 px-2.5 py-1 rounded-lg">☑️ {{ trim($etiket) }}</span>
                                    @endforeach
                                @endif
                                @if($t->yonetici_onayi_sart)
                                    <span class="text-xs bg-amber-950 text-amber-300 border border-amber-700 px-2.5 py-1 rounded-lg font-semibold">🔒 Yönetici Onayı Şart</span>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs pt-2">
                                <div class="bg-slate-900/40 p-3 rounded-lg border border-slate-700/50">
                                    <strong class="text-amber-400">Tespit Edilen Kök Neden:</strong> <span class="text-slate-300">{{ $t->kok_neden }}</span>
                                </div>
                                <div class="bg-slate-900/40 p-3 rounded-lg border border-slate-700/50">
                                    <strong class="text-red-400">Operasyonel Risk:</strong> <span class="text-slate-300">{{ $t->operasyonel_risk }}</span>
                                </div>
                            </div>

                            <div class="flex flex-wrap justify-between items-center pt-3 border-t border-slate-700/60 gap-2">
                                <div class="flex flex-wrap gap-2">
                                    @if($t->durum !== 'Açık (L1 Triyaj)')
                                    <form action="{{ route('web.ticket.status', $t->id) }}" method="POST">
                                        @csrf <input type="hidden" name="yeni_durum" value="Açık (L1 Triyaj)">
                                        <button type="submit" class="text-xs bg-slate-700 hover:bg-slate-500 text-slate-200 px-3 py-1.5 rounded-lg transition font-medium">⏪ Açığa Al</button>
                                    </form>
                                    @endif
                                    @if($t->durum !== 'İnceleniyor (L2 Teknik Ekip)')
                                    <form action="{{ route('web.ticket.status', $t->id) }}" method="POST">
                                        @csrf <input type="hidden" name="yeni_durum" value="İnceleniyor (L2 Teknik Ekip)">
                                        <button type="submit" class="text-xs bg-slate-700 hover:bg-blue-600 text-slate-200 px-3 py-1.5 rounded-lg transition font-medium">🛠️ L2'ye Ata</button>
                                    </form>
                                    @endif
                                    @if($t->durum !== 'Bloke Edildi (Onay Bekliyor)')
                                    <form action="{{ route('web.ticket.status', $t->id) }}" method="POST">
                                        @csrf <input type="hidden" name="yeni_durum" value="Bloke Edildi (Onay Bekliyor)">
                                        <button type="submit" class="text-xs bg-slate-700 hover:bg-amber-600 text-slate-200 px-3 py-1.5 rounded-lg transition font-medium">🔒 Bloke Et</button>
                                    </form>
                                    @endif
                                    @if($t->durum !== 'Çözüldü')
                                    <form action="{{ route('web.ticket.status', $t->id) }}" method="POST">
                                        @csrf <input type="hidden" name="yeni_durum" value="Çözüldü">
                                        <button type="submit" class="text-xs bg-slate-700 hover:bg-emerald-600 text-slate-200 px-3 py-1.5 rounded-lg transition font-medium">✅ Çözüldü İşaretle</button>
                                    </form>
                                    @endif
                                </div>
                                <form action="{{ route('web.ticket.destroy', $t->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-400 hover:underline px-2 py-1">Kaydı Sil</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- GİZLİ ADMİN ŞİFRE SIFIRLAMA PENCERESİ --}}
    <div id="admin_sifre_modal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex justify-center items-center z-50 p-4">
        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 w-full max-w-sm shadow-2xl relative">
            <button type="button" onclick="document.getElementById('admin_sifre_modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-white font-bold text-lg">✕</button>
            <h3 class="text-lg font-bold text-white mb-2">🔑 Admin Şifresini Değiştir</h3>
            <p class="text-xs text-slate-400 mb-5">Ana IT yöneticisi (admin) giriş şifrenizi buradan güvenle güncelleyebilirsiniz.</p>
            
            <form action="{{ route('admin.sifre.degistir') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="text-xs text-slate-300 block mb-1">Mevcut (Eski) Şifreniz</label>
                    <input type="password" name="eski_sifre" required class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white focus:border-amber-500 focus:outline-none">
                </div>
                <div>
                    <label class="text-xs text-slate-300 block mb-1">Yeni Admin Şifresi</label>
                    <input type="password" name="yeni_sifre" required minlength="6" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white focus:border-amber-500 focus:outline-none">
                </div>
                <button type="submit" class="w-full bg-amber-600 hover:bg-amber-500 text-white font-bold py-3 rounded-xl shadow-lg transition text-sm">Şifreyi Güncelle</button>
            </form>
        </div>
    </div>

    <script>
        if (localStorage.getItem('theme') === 'light') { document.documentElement.classList.add('light-mode'); if(document.getElementById('tema_ikon')) { document.getElementById('tema_ikon').innerText = '🌙'; document.getElementById('tema_yazı').innerText = 'Koyu Tema'; } }
        function temaDegistir() {
            const html = document.documentElement; const ikon = document.getElementById('tema_ikon'); const yazi = document.getElementById('tema_yazı');
            if (html.classList.contains('light-mode')) { html.classList.remove('light-mode'); localStorage.setItem('theme', 'dark'); if(ikon) { ikon.innerText = '☀️'; yazi.innerText = 'Açık Tema'; } } 
            else { html.classList.add('light-mode'); localStorage.setItem('theme', 'light'); if(ikon) { ikon.innerText = '🌙'; yazi.innerText = 'Koyu Tema'; } }
        }
        function toggleAccordion(id) {
            const detay = document.getElementById('detay-' + id); const ok = document.getElementById('ok-' + id);
            if (detay.classList.contains('hidden')) { detay.classList.remove('hidden'); ok.classList.add('rotate-180'); } 
            else { detay.classList.add('hidden'); ok.classList.remove('rotate-180'); }
        }
    </script>
</body>
</html>