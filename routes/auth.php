<?php
/**
 * routes/auth.php DINONAKTIFKAN
 *
 * Kita memakai AuthController kustom (routes/web.php) untuk:
 * - /login (GET & POST)
 * - /logout (GET)
 * - Redirect role-based (staff -> /dashboard, pelanggan -> /home)
 *
 * Jika file ini di-include, akan bentrok dengan guard 'web' bawaan Breeze/Fortify
 * dan menyebabkan /dashboard (auth:staff) selalu mental ke /login.
 *
 * Biarkan file ini kosong, atau jangan di-include dari routes/web.php.
 */
