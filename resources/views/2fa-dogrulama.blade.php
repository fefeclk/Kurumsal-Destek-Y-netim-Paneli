<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA E-posta Doğrulama</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
    <style>
        html.light-mode body { background-color: #f1f5f9 !important; color: #0f172a !important; }
        html.light-mode .bg-slate-800 { background-color: #ffffff !important; border-color: #cbd5e1 !important; color: #0f172a !important; }
        html.light-mode .bg-slate-900 { background-color: #e2e8f0 !important; color: #0f172a !important; }
        html.light-mode .text-slate-100, html.light-mode .text-white { color: #0f172a !important; }
        html.light-mode .text-slate-300 { color: #334155 !important; }
        html.light-mode .border-slate-700 { border-color: #cbd5e1 !important; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex items-center justify-center p-6 transition-colors duration-300 relative">
    
    <div class="absolute top-6 right-6 flex gap-2">
        <form action="{{ route('calisan.logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-slate-800 border border-slate-700 hover:bg-red-900/80 text-slate-400 hover:text-white text-xs px-3 py-2 rounded-xl transition shadow-lg">
                Girişe Dön
            </button>
        </form>
        <button type="button" onclick="temaDegistir()" class="bg-slate-800 border border-slate-700 hover:bg-slate-700 text-slate-200 text-xs px-3 py-2 rounded-xl transition shadow-lg">
            <span id="tema_ikon">☀️</span> Tema
        </button>
    </div>

    <div class="max-w-md w-full bg-slate-800 p-8 rounded-2xl border border-slate-700 shadow-2xl space-y-6">
        <div class="text-center space-y-2">
            <span class="bg-purple-500/20 text-purple-400 border border-purple-500/40 text-xs font-mono px-3 py-1 rounded-full">GÜVENLİK ADIMI (2FA)</span>
            <h1 class="text-2xl font-bold text-white pt-2">🛡️ Hesabınızı Doğrulayın</h1>
            <p class="text-xs text-slate-400">İlk girişiniz olduğu için <b>{{ auth()->user()->email }}</b> adresine gönderilen 6 haneli doğrulama kodunu giriniz.</p>
        </div>

        @if(session('hata'))
            <div class="bg-red-950/80 border border-red-600 text-red-200 px-4 py-3 rounded-xl text-xs font-semibold text-center shadow">⚠️ {{ session('hata') }}</div>
        @endif

        <form action="{{ route('2fa.post') }}" method="POST" class="space-y-4 text-sm">
            @csrf
            <div>
                <input type="text" name="kod" required maxlength="6" placeholder="Örn: 123456" class="w-full bg-slate-900 border border-slate-700 rounded-xl p-4 text-center tracking-[0.5em] font-mono text-lg text-white focus:border-purple-500 focus:outline-none">
            </div>
            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 text-white font-bold py-3.5 rounded-xl shadow-lg transition">Kodu Doğrula ve Giriş Yap →</button>
        </form>

        
    </div>

    <script>
        if (localStorage.getItem('theme') === 'light') { document.documentElement.classList.add('light-mode'); if(document.getElementById('tema_ikon')) document.getElementById('tema_ikon').innerText = '🌙'; }
        function temaDegistir() {
            const html = document.documentElement;
            const ikon = document.getElementById('tema_ikon');
            if (html.classList.contains('light-mode')) {
                html.classList.remove('light-mode'); localStorage.setItem('theme', 'dark'); if(ikon) ikon.innerText = '☀️';
            } else {
                html.classList.add('light-mode'); localStorage.setItem('theme', 'light'); if(ikon) ikon.innerText = '🌙';
            }
        }
    </script>
</body>
</html>