<?php

namespace App\Enums;

enum UnidadeMedidaEstoque: string
{
    case Unidade = 'unidade';
    case Caixa = 'caixa';
    case Pacote = 'pacote';
    case Litro = 'litro';
    case Metro = 'metro';
    case Kg = 'kg';
    case Outro = 'outro';

    public function label(): string
    {
        return match ($this) {
            self::Unidade => 'Unidade',
            self::Caixa => 'Caixa',
            self::Pacote => 'Pacote',
            self::Litro => 'Litro',
            self::Metro => 'Metro',
            self::Kg => 'Kg',
            self::Outro => 'Outro',
        };
    }

    public function abbreviation(): string
    {
        return match ($this) {
            self::Unidade => 'un',
            self::Caixa => 'cx',
            self::Pacote => 'pct',
            self::Litro => 'l',
            self::Metro => 'm',
            self::Kg => 'kg',
            self::Outro => 'outro',
        };
    }
}
