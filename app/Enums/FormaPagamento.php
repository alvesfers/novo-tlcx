<?php

namespace App\Enums;

enum FormaPagamento: string
{
    case Dinheiro = 'dinheiro';
    case Pix = 'pix';
    case Transferencia = 'transferencia';
    case Cartao = 'cartao';
    case Cheque = 'cheque';
    case Outro = 'outro';

    public function label(): string
    {
        return match($this) {
            self::Dinheiro => 'Dinheiro',
            self::Pix => 'PIX',
            self::Transferencia => 'Transferência',
            self::Cartao => 'Cartão',
            self::Cheque => 'Cheque',
            self::Outro => 'Outro',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
