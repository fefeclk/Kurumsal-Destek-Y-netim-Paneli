<?php

return [
    'partner_api_key' => env('PARTNER_API_KEY', 'kurumsal-2026-secret'),
    'admin_user' => env('ADMIN_USER', 'admin'),
    
    // Admin şifresini de güvenli config alanına ekliyoruz
    'admin_pass' => env('ADMIN_PASS', '123456'),
];