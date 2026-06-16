<?php

namespace App\Enums;

enum TipoMovimentoFinanceiro: string
{
    case Entrada = 'entrada';
    case Saida = 'saida';

    public function label(): string
    {
        return match($this) {
            self::Entrada => 'Entrada',
            self::Saida => 'Saída',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
