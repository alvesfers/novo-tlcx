<?php

namespace App\Enums;

enum TipoUsuario: string
{
    case Admin = 'admin';
    case Diocese = 'diocese';
    case Nucleo = 'nucleo';
    case Secretaria = 'secretaria';

    public function label(): string
    {
        return match($this) {
            self::Admin => 'Administrador',
            self::Diocese => 'Diocese',
            self::Nucleo => 'Núcleo',
            self::Secretaria => 'Secretaria',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
