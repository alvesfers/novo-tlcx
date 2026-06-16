<?php

namespace App\Enums;

enum CargoDirigente: string
{
    case Dirigente = 'dirigente';
    case Coordenador = 'coordenador';

    public function label(): string
    {
        return match($this) {
            self::Dirigente => 'Dirigente',
            self::Coordenador => 'Coordenador',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function isDirigente(): bool
    {
        return $this === self::Dirigente;
    }

    public function isCoordenador(): bool
    {
        return $this === self::Coordenador;
    }
}
