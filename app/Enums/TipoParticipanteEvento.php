<?php

namespace App\Enums;

enum TipoParticipanteEvento: string
{
    case Dirigente = 'dirigente';
    case Externo = 'externo';

    public function label(): string
    {
        return match($this) {
            self::Dirigente => 'Dirigente',
            self::Externo => 'Externo',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
