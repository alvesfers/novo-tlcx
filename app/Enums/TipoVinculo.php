<?php

namespace App\Enums;

enum TipoVinculo: string
{
    case Principal = 'principal';
    case Adicional = 'adicional';
    case Coordenacao = 'coordenacao';

    public function label(): string
    {
        return match($this) {
            self::Principal => 'Principal',
            self::Adicional => 'Adicional',
            self::Coordenacao => 'Coordenação',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function isPrincipal(): bool
    {
        return $this === self::Principal;
    }

    public function isAdicional(): bool
    {
        return $this === self::Adicional;
    }

    public function isCoordenacao(): bool
    {
        return $this === self::Coordenacao;
    }
}
