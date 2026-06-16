<?php

namespace App\Enums;

enum StatusEvento: string
{
    case Rascunho = 'rascunho';
    case Publicado = 'publicado';
    case Encerrado = 'encerrado';
    case Cancelado = 'cancelado';

    public function label(): string
    {
        return match($this) {
            self::Rascunho => 'Rascunho',
            self::Publicado => 'Publicado',
            self::Encerrado => 'Encerrado',
            self::Cancelado => 'Cancelado',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
