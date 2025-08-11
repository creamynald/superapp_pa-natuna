<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms;
use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Http\Responses\Auth\Contracts\LoginResponse; // <-- penting
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;

class Login extends BaseLogin
{
    protected function getForms(): array
    {
        return [
            'form' => $this->makeForm()->schema([
                Forms\Components\TextInput::make('login')->label('Email atau NIP')->required(),
                Forms\Components\TextInput::make('password')->password()->required(),
                Forms\Components\Checkbox::make('remember'),
            ]),
        ];
    }

    public function authenticate(): ?LoginResponse // <-- ubah tipe return
    {
        $data = $this->form->getState();

        $login    = trim($data['login'] ?? '');
        $password = $data['password'] ?? '';
        $remember = (bool) ($data['remember'] ?? false);

        // 1) Login via email
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            if (Auth::attempt(['email' => $login, 'password' => $password], $remember)) {
                session()->regenerate();
                return app(LoginResponse::class); // <-- bukan redirect()
            }

            $this->addError('login', __('auth.failed'));
            return null;
        }

        // 2) Login via NIP
        $employee = Employee::with('user')->where('nip', $login)->first();

        if (! $employee || ! $employee->user || ! Hash::check($password, $employee->user->password)) {
            $this->addError('login', __('auth.failed'));
            return null;
        }

        Auth::login($employee->user, $remember);
        session()->regenerate();

        return app(LoginResponse::class); // <-- balikan yang benar
    }
}
