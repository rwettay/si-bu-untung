<?php

return [

    /*
    |----------------------------------------------------------------------
    | Authentication Defaults
    |----------------------------------------------------------------------
    */
    'defaults' => [
        'guard' => 'staff',  // Ganti default guard ke 'staff' agar login menggunakan guard staff
        'passwords' => 'staff',  // Ganti password broker ke 'staff'
    ],

    /*
    |----------------------------------------------------------------------
    | Authentication Guards
    |----------------------------------------------------------------------
    */
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Guard untuk staff (untuk admin panel Filament)
        'staff' => [
            'driver'   => 'session',
            'provider' => 'staff', 
        ],

        // Guard untuk pelanggan
        'pelanggan' => [
            'driver' => 'session',
            'provider' => 'pelanggan', 
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | User Providers
    |----------------------------------------------------------------------
    */
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class, // Model untuk pelanggan
        ],
        'staff' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Staff::class, // Model untuk staff
        ],
        'pelanggan' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Pelanggan::class, // Model untuk pelanggan
        ],
    ],

    /*
    |----------------------------------------------------------------------
    | Passwords
    |----------------------------------------------------------------------
    */
    'passwords' => [
        'staff' => [
            'provider' => 'staff',
            'table'    => 'password_resets',
            'expire'   => 60,
            'throttle' => 60,
        ],

        'pelanggan' => [
            'provider' => 'pelanggan',
            'table'    => 'password_resets',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
