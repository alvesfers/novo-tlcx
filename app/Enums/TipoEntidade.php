<?php

namespace App\Enums;

enum TipoEntidade: string
{
    case Diocese = 'diocese';
    case Nucleo = 'nucleo';
    case Secretaria = 'secretaria';

    public function label(): string
    {
        return match($this) {
            self::Diocese => 'Diocese',
            self::Nucleo => 'Núcleo',
            self::Secretaria => 'Secretaria',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function isDiocese(): bool
    {
        return $this === self::Diocese;
    }

    public function isNucleo(): bool
    {
        return $this === self::Nucleo;
    }

    public function isSecretaria(): bool
    {
        return $this === self::Secretaria;
    }
}
