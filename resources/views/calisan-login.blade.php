<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Çalışan Girişi - Kurumsal Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
    <style>
        html.light-mode body { background-color: #f1f5f9 !important; color: #0f172a !important; }
        html.light-mode .bg-slate-800 { background-color: #ffffff !important; border-color: #cbd5e1 !important; color: #0f172a !important; }
        html.light-mode .bg-slate-900 { background-color: #e2e8f0 !important; color: #0f172a !important; }
        html.light-mode .text-slate-100, html.light-mode .text-white { color: #0f172a !important; }
        html.light-mode .text-slate-300 { color: #334155 !important; }
        html.light-mode .text-slate-400 { color: #64748b !important; }
        html.light-mode .border-slate-700 { border-color: #cbd5e1 !important; }
        html.light-mode :not(button):not(a).text-slate-100, html.light-mode :not(button):not(a).text-white { color: #0f172a !important; }
        html.light-mode button.bg-slate-800 { background-color: #f1f5f9 !important; color: #0f172a !important; border: 1px solid #cbd5e1; }
        html.light-mode button.bg-slate-800:hover { background-color: #e2e8f0 !important; }
        html.light-mode a.bg-blue-600, html.light-mode button.bg-blue-600 { color: #ffffff !important; }
        html.light-mode .bg-amber-600 { background-color: #d97706 !important; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-6 transition-colors duration-300 relative">
    
    <div class="absolute top-6 right-6">
        <button type="button" onclick="temaDegistir()" class="bg-slate-800 border border-slate-700 hover:bg-slate-700 text-slate-200 text-xs px-3 py-2 rounded-xl transition shadow-lg flex items-center gap-1.5">
            <span id="tema_ikon">☀️</span> <span id="tema_yazı" class="hidden sm:inline">Açık Tema</span>
        </button>
    </div>

    <div class="max-w-md w-full bg-slate-800 p-8 rounded-2xl border border-slate-700 shadow-2xl space-y-6">
        <div class="text-center space-y-2">
            <span class="bg-blue-500/20 text-blue-400 border border-blue-500/40 text-xs font-mono px-3 py-1 rounded-full">KURUMSAL İÇ PORTAL</span>
            <h1 class="text-2xl font-bold text-white pt-2">👨‍💻 Çalışan Girişi</h1>
            <p class="text-xs text-slate-400">Destek talebi oluşturmak için e-posta ve şifrenizle giriş yapın.</p>
        </div>

        @if(session('hata'))
            <div class="bg-red-950/80 border border-red-600 text-red-200 px-4 py-3 rounded-xl text-xs font-semibold text-center shadow">⚠️ {{ session('hata') }}</div>
        @endif
        @if(session('basari'))
            <div class="bg-emerald-950/80 border border-emerald-600 text-emerald-200 px-4 py-3 rounded-xl text-xs font-semibold text-center shadow">✅ {{ session('basari') }}</div>
        @endif

        <form action="{{ route('calisan.login.post') }}" method="POST" class="space-y-4 text-sm">
            @csrf
            <div>
                <label class="text-xs text-slate-300 font-semibold block mb-1.5">Kurumsal E-posta</label>
                <input type="email" name="email" required class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white focus:border-blue-500 focus:outline-none">
            </div>
            <div>
                <label class="text-xs text-slate-300 font-semibold block mb-1.5">Şifre</label>
                <input type="password" name="password" required class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white focus:border-blue-500 focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-xl shadow-lg transition">Giriş Yap →</button>
        </form>

        <!-- EKLENEN KISIM: ŞİFREMİ UNUTTUM -->
        <div class="pt-4 border-t border-slate-700 text-center text-xs text-slate-400 space-y-2">
            <div><button type="button" onclick="document.getElementById('sifre_modal').classList.remove('hidden')" class="text-amber-400 hover:underline">Şifremi Unuttum</button></div>
            <div>Hesabınız yoksa lütfen <a href="{{ route('admin.login') }}" class="text-blue-400 hover:underline">IT Departmanı (Admin)</a> ile görüşün.</div>
        </div>
    </div>

    <!-- GİZLİ ŞİFRE SIFIRLAMA PENCERESİ (MODAL) -->
    <div id="sifre_modal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-sm flex justify-center items-center z-50 p-4">
        <div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 w-full max-w-sm shadow-2xl relative">
            <button type="button" onclick="document.getElementById('sifre_modal').classList.add('hidden')" class="absolute top-4 right-4 text-slate-400 hover:text-white font-bold text-lg">✕</button>
            <h3 class="text-lg font-bold text-white mb-2">🔑 Şifre Sıfırlama Talebi</h3>
            <p class="text-xs text-slate-400 mb-5">Kurumsal e-posta adresinizi girin. IT departmanına şifre sıfırlama talebiniz iletilecektir.</p>
            
            <form action="{{ route('calisan.sifre.talep') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <input type="email" name="email" required placeholder="ornek@sirket.com" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white focus:border-amber-500 focus:outline-none">
                </div>
                <button type="submit" class="w-full bg-amber-600 hover:bg-amber-500 text-white font-bold py-3 rounded-xl shadow-lg transition text-sm">Talebi Gönder</button>
            </form>
        </div>
    </div>

    <script>
        if (localStorage.getItem('theme') === 'light') { document.documentElement.classList.add('light-mode'); if(document.getElementById('tema_ikon')) { document.getElementById('tema_ikon').innerText = '🌙'; if(document.getElementById('tema_yazı')) document.getElementById('tema_yazı').innerText = 'Koyu Tema'; } }
        function temaDegistir() {
            const html = document.documentElement; const ikon = document.getElementById('tema_ikon'); const yazi = document.getElementById('tema_yazı');
            if (html.classList.contains('light-mode')) { html.classList.remove('light-mode'); localStorage.setItem('theme', 'dark'); if(ikon) ikon.innerText = '☀️'; if(yazi) yazi.innerText = 'Açık Tema'; } 
            else { html.classList.add('light-mode'); localStorage.setItem('theme', 'light'); if(ikon) ikon.innerText = '🌙'; if(yazi) yazi.innerText = 'Koyu Tema'; }
        }
    </script>
</body>
</html>