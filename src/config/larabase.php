<?php

return [
    'url' => env('SUPABASE_URL'),
    'key' => env('SUPABASE_KEY'),
    'service_role' => env('SUPABASE_SERVICE_KEY'),
    'user_model' => env('SUPABASE_USER', \Bytecraftnz\Larabase\Models\User::class),
];
