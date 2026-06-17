<?php

namespace App\Enums;

enum PrioridadeTarefa: string
{
    case Baixa = 'baixa';
    case Media = 'media';
    case Alta = 'alta';
    case Urgente = 'urgente';

    public function label(): string
    {
        return match ($this) {
            self::Baixa => 'Baixa',
            self::Media => 'Média',
            self::Alta => 'Alta',
            self::Urgente => 'Urgente',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Baixa => 'gray',
            self::Media => 'yellow',
            self::Alta => 'orange',
            self::Urgente => 'red',
        };
    }
}
