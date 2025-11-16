<?php

return [
    // Legacy (kalau masih dipakai di tempat lain)
    'prefix' => env('BARANG_PREFIX', 'RKK'),

    // Mapping kategori → prefix (boleh kamu ubah/ tambah)
    'prefix_map' => [
        'rokok'   => 'RKK',
        'minuman' => 'MNM',
        'makanan' => 'MKN',
    ],

    // Panjang digit nomor sequence (PREFIX + nomor)
    'seq_pad' => (int) env('BARANG_SEQ_PAD', 3), // contoh: 3 → RKK001, MNM002
];
