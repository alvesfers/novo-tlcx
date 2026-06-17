<?php

namespace App\Enums;

enum StatusItemEstoque: string
{
    case Ativo = 'ativo';
    case Inativo = 'inativo';
    case Esgotado = 'esgotado';

    public function label(): string
    {
        return match ($this) {
            self::Ativo => 'Ativo',
            self::Inativo => 'Inativo',
            self::Esgotado => 'Esgotado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Ativo => 'green',
            self::Inativo => 'gray',
            self::Esgotado => 'red',
        };
    }
}
