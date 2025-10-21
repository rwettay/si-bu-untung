<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Semua boleh akses form login.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi.
     */
    public function rules(): array
    {
        return [
            // Satu field untuk email ATAU username
            'identifier' => ['required', 'string'],
            'password'   => ['required', 'string'],
            'remember'   => ['nullable'],
        ];
    }

    /**
     * Proses autentikasi: coba guard staff dulu, jika gagal coba guard pelanggan.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $id  = $this->input('identifier');
        $pwd = $this->input('password');

        $isEmail = filter_var($id, FILTER_VALIDATE_EMAIL);

        // Susun kredensial untuk masing-masing guard
        $staffCred = $isEmail
            ? ['email' => $id, 'password' => $pwd]
            : ['username' => $id, 'password' => $pwd];

        $custCred = $staffCred; // sama saja (email/username + password)

        // Coba STAFF dulu
        if (Auth::guard('staff')->attempt($staffCred, $this->boolean('remember'))) {
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // Lalu PELANGGAN
        if (Auth::guard('pelanggan')->attempt($custCred, $this->boolean('remember'))) {
            RateLimiter::clear($this->throttleKey());
            return;
        }

        // Gagal keduanya
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'identifier' => trans('auth.failed'),
        ]);
    }

    /**
     * Rate limit checker.
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'identifier' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Kunci throttle berdasarkan identifier + IP.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower((string) $this->input('identifier')).'|'.$this->ip());
    }
}
