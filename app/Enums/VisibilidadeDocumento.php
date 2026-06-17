<?php

namespace App\Enums;

enum VisibilidadeDocumento: string
{
    case Publico = 'publico';
    case Privado = 'privado';

    public function label(): string
    {
        return match ($this) {
            self::Publico => 'Público',
            self::Privado => 'Privado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Publico => 'green',
            self::Privado => 'red',
        };
    }
}
