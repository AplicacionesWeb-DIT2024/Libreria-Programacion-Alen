<?php

namespace App\Enums;

enum Role: string
{
    case Cliente = 'cliente';
    case Admin = 'admin';
    case SuperAdmin = 'superadmin';

    public function label(): string
    {
        return match ($this) {
            self::Cliente => 'Cliente',
            self::Admin => 'Administrador',
            self::SuperAdmin => 'Superadministrador',
        };
    }

    public function esPersonalAdministrativo(): bool
    {
        return in_array($this, [self::Admin, self::SuperAdmin], true);
    }
}