<?php

namespace App\Enums;

enum TipoSecretaria: string
{
    case Aberta = 'aberta';
    case Fechada = 'fechada';

    public function label(): string
    {
        return match($this) {
            self::Aberta => 'Aberta',
            self::Fechada => 'Fechada',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function isAberta(): bool
    {
        return $this === self::Aberta;
    }

    public function isFechada(): bool
    {
        return $this === self::Fechada;
    }
}
