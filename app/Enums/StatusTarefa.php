<?php

namespace App\Enums;

enum StatusTarefa: string
{
    case Pendente = 'pendente';
    case EmAndamento = 'em_andamento';
    case Concluida = 'concluida';
    case Cancelada = 'cancelada';

    public function label(): string
    {
        return match ($this) {
            self::Pendente => 'Pendente',
            self::EmAndamento => 'Em Andamento',
            self::Concluida => 'Concluída',
            self::Cancelada => 'Cancelada',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pendente => 'gray',
            self::EmAndamento => 'blue',
            self::Concluida => 'green',
            self::Cancelada => 'red',
        };
    }
}
