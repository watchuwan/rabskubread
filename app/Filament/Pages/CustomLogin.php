<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Component;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\ValidationException;

class CustomLogin extends BaseLogin
{
    // protected function getLoginFormComponentName(): string
    // {
    //     return 'login';
    // }

    //     protected function getLoginValidationRules(): array
    // {
    //     return [
    //         'login' => ['required'],
    //         'password' => ['required'],
    //     ];
    // }
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // $this->getLoginFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('auth.name_or_email'))
            ->required()
            ->autofocus();
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        $loginType = filter_var($data['email'], FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'name';

        return [
            $loginType => $data['email'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.login' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }

}
