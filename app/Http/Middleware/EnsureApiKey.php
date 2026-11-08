<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        // Header'dan X-API-KEY veya tarayıcıdan kolay test için ?api_key= parametresini okur
        $key = (string) ($request->header('X-API-KEY') ?? $request->query('api_key'));
        // ESKİ VE HATALI HALİ (BUNU SİL):
// $gecerliKey = (string) env('PARTNER_API_KEY', 'kurumsal-2026-secret');

// YENİ VE GÜVENLİ HALİ (BUNU YAPIŞTIR):
$gecerliKey = (string) config('kurumsal.partner_api_key');
        if ($key === '' || ! hash_equals($gecerliKey, $key)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Geçersiz API anahtarı (Yetkisiz Erişim - 401)'
            ], 401);
        }

        return $next($request);
    }
}