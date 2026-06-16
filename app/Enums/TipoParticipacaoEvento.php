<?php

namespace App\Enums;

enum TipoParticipacaoEvento: string
{
    case Organizadora = 'organizadora';
    case Participante = 'participante';
    case Apoio = 'apoio';

    public function label(): string
    {
        return match($this) {
            self::Organizadora => 'Organizadora',
            self::Participante => 'Participante',
            self::Apoio => 'Apoio',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
