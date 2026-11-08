<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Giriş yapılmış mı? 2. Yapan kişi Admin mi?
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request); // İçeri al
        }
        
        // Admin değilse geldiği yere geri şutla
        return redirect('/')->with('hata', 'Bu sayfaya erişim yetkiniz (Admin) bulunmamaktadır!');
    }
}