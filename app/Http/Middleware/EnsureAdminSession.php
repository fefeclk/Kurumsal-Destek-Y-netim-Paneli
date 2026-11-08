<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminSession
{
    public function handle(Request $request, Closure $next): Response
    {
        // Oturumda admin yetkisi yoksa giriş sayfasına yönlendir
        if (! session()->get('it_admin_logged_in')) {
            return redirect()->route('admin.login')
                ->with('hata', 'Bu alana erişmek için IT Yöneticisi girişi yapmalısınız (403 Yetki Koruması).');
        }

        return $next($request);
    }
}