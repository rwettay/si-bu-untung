<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | Default guard & password broker yang dipakai aplikasi. Di sini
    | kita set ke "pelanggan" agar login pelanggan bekerja out-of-the-box.
    |
    */

    'defaults' => [
        'guard' => 'pelanggan',
        'passwords' => 'pelanggan',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Kita definisikan 3 guard: web (bawaan), staff (admin), pelanggan (user).
    | Masing-masing pakai driver "session" dan provider sesuai modelnya.
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'staff' => [
            'driver' => 'session',
            'provider' => 'staff',
        ],

        'pelanggan' => [
            'driver' => 'session',
            'provider' => 'pelanggan',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | Mapping guard -> provider -> model Eloquent.
    | Pastikan model yang disebutkan ada dan namespace-nya benar.
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,       // model default (jika masih dipakai)
        ],

        'staff' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Staff::class,      // model admin/staff
        ],

        'pelanggan' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Pelanggan::class,  // model pelanggan (Authenticatable)
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Reset Passwords
    |--------------------------------------------------------------------------
    |
    | Untuk Laravel 10+, nama tabelnya "password_reset_tokens".
    | Jika versi lama, ganti ke "password_resets".
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],

        'staff' => [
            'provider' => 'staff',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],

        'pelanggan' => [
            'provider' => 'pelanggan',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => 10800,

];
