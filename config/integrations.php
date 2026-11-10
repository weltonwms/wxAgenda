<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Integrações externas
    |--------------------------------------------------------------------------
    |
    | Credenciais e links lidos do .env. Com config:cache, env() só funciona
    | dentro de arquivos de configuração; o restante da aplicação usa config().
    |
    */

    'telegram' => [
        'token' => env('TELEGRAM_TOKEN', ''),
        'base_url' => env('TELEGRAM_BASE_URL', ''),
    ],

    'sso' => [
        'secret' => env('SSO_SECRET_KEY'),
    ],

    'woocommerce' => [
        'webhook_secret' => env('WC_WEBHOOK_SECRET'),
    ],

    'tokens' => [
        'api' => env('STATIC_TOKEN_API'),
        'students' => env('STATIC_TOKEN_STUDENTS_API'),
    ],

    'drive' => [
        'student' => env('STUDENT_LINK_DRIVE', ''),
        'teacher' => env('TEACHER_LINK_DRIVE', ''),
    ],

];
