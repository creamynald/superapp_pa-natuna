<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Login extends BaseLogin
{
    /**
     * Ubah komponen email bawaan supaya:
     * - label: "Email atau NIP"
     * - type: text (bukan email)
     * - tanpa rule "email"
     */
    protected function getEmailFormComponent(): TextInput
    {
        // Boleh bangun sendiri, tanpa memanggil parent, supaya bersih dari rule bawaan
        return TextInput::make('email')
            ->label('Email atau NIP')
            ->required()
            ->type('text')         // HTML input type text (bukan email)
            ->email(false)         // matikan validasi email otomatis Filament
            ->rules(['required'])  // jangan pakai 'required|string' jadi satu
            ->autocomplete('username')
            ->autocapitalize('off');
    }

    /**
     * Auth: dukung email ATAU NIP (NIP di tabel pegawais)
     */
    public function authenticate(): ?LoginResponse
    {
        $data     = $this->form->getState();
        $login    = trim((string) ($data['email'] ?? ''));   // tetap gunakan key 'email'
        $password = (string) ($data['password'] ?? '');
        $remember = (bool) ($data['remember'] ?? false);

        // 1) Jika format email valid → login via kolom users.email
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            if (Auth::attempt(['email' => $login, 'password' => $password], $remember)) {
                session()->regenerate();
                return app(LoginResponse::class);
            }

            $this->addError('email', __('auth.failed'));
            return null;
        }

        // 2) Jika bukan email → anggap NIP; cari user lewat relasi Pegawai
        $pegawai = \App\Models\Kesekretariatan\Pegawai::with('user')
            ->where('nip', $login)
            ->first();

        if ($pegawai && $pegawai->user && Hash::check($password, $pegawai->user->password)) {
            Auth::login($pegawai->user, $remember);
            session()->regenerate();
            return app(LoginResponse::class);
        }

        $this->addError('email', __('auth.failed'));
        return null;
    }
}
