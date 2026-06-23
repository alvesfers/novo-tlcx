<?php

namespace App\Enums;

enum TipoBarzinho: string
{
    case CONSUMO_DEPOIS = 'consumo_depois';
    case PAGA_NA_HORA = 'paga_na_hora';
    case PRE_PAGO = 'pre_pago';

    public function label(): string
    {
        return match ($this) {
            self::CONSUMO_DEPOIS => 'Consumo + Pagar Depois',
            self::PAGA_NA_HORA => 'Paga na Hora',
            self::PRE_PAGO => 'Pré-Pago com Recarga',
        };
    }

    public function descricao(): string
    {
        return match ($this) {
            self::CONSUMO_DEPOIS => 'Pessoa consome e paga no final do evento',
            self::PAGA_NA_HORA => 'Pessoa paga imediatamente ao consumir',
            self::PRE_PAGO => 'Pessoa recarrega saldo e usa para consumir',
        };
    }
}
