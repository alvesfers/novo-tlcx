<?php

namespace App\Enums;

enum EscopoEvento: string
{
    case Coordenadores = 'coordenadores';
    case Dirigentes = 'dirigentes';
    case Ambos = 'ambos';
    case Externos = 'externos';
    case Publico = 'publico';

    public function label(): string
    {
        return match($this) {
            self::Coordenadores => 'Coordenadores',
            self::Dirigentes => 'Dirigentes',
            self::Ambos => 'Ambos (Coordenadores e Dirigentes)',
            self::Externos => 'Externos',
            self::Publico => 'Público',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
