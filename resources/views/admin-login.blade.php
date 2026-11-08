<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Admin Girişi</title>
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
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-6 transition-colors duration-300 relative">
    
    <!-- Sağ Üst Tema Butonu -->
    <div class="absolute top-6 right-6">
        <button type="button" onclick="temaDegistir()" class="bg-slate-800 border border-slate-700 hover:bg-slate-700 text-slate-200 text-xs px-3 py-2 rounded-xl transition shadow-lg">
            <span id="tema_ikon">☀️</span> Tema
        </button>
    </div>

    <div class="max-w-md w-full bg-slate-800 p-8 rounded-2xl border border-slate-700 shadow-2xl space-y-6">
        <div class="text-center space-y-2">
            <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/40 text-xs font-mono px-3 py-1 rounded-full">ROL BAZLI ERİŞİM</span>
            <h1 class="text-2xl font-bold text-white pt-2">🛡️ IT Admin Girişi</h1>
            <p class="text-xs text-slate-400">Yönetim paneline erişmek için yetkili girişi yapın.</p>
        </div>

        @if(session('hata'))
            <div class="bg-red-950/80 border border-red-600 text-red-200 px-4 py-3 rounded-xl text-xs text-center font-semibold">⚠️ {{ session('hata') }}</div>
        @endif
        @if(session('basari'))
            <div class="bg-emerald-950/80 border border-emerald-600 text-emerald-200 px-4 py-3 rounded-xl text-xs text-center font-semibold">✅ {{ session('basari') }}</div>
        @endif

        <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-4 text-sm">
            @csrf
            <div>
                <label class="text-xs text-slate-300 font-semibold block mb-1.5">Yönetici Kullanıcı Adı</label>
                <input type="text" name="kullanici_adi" value="admin" required class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white focus:border-blue-500 focus:outline-none">
            </div>
            <div>
                <label class="text-xs text-slate-300 font-semibold block mb-1.5">Şifre</label>
                <input type="password" name="sifre" value="123456" required class="w-full bg-slate-900 border border-slate-700 rounded-xl p-3 text-white focus:border-blue-500 focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-900/40 transition">Giriş Yap →</button>
        </form>

        <div class="pt-4 border-t border-slate-700 flex justify-between items-center text-xs text-slate-400">
            
            <a href="{{ route('calisan.form') }}" class="text-blue-400 hover:underline">← Form'a Dön</a>
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