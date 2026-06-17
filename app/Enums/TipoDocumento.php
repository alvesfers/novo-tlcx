<?php

namespace App\Enums;

enum TipoDocumento: string
{
    case Geral = 'geral';
    case Ata = 'ata';
    case Financeiro = 'financeiro';
    case Evento = 'evento';
    case Formacao = 'formacao';
    case Liturgia = 'liturgia';
    case Imagem = 'imagem';
    case Outro = 'outro';

    public function label(): string
    {
        return match ($this) {
            self::Geral => 'Geral',
            self::Ata => 'Ata',
            self::Financeiro => 'Financeiro',
            self::Evento => 'Evento',
            self::Formacao => 'Formação',
            self::Liturgia => 'Liturgia',
            self::Imagem => 'Imagem',
            self::Outro => 'Outro',
        };
    }
}
