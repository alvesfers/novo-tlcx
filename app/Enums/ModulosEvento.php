<?php

namespace App\Enums;

enum ModulosEvento: string
{
    case DETALHES = 'detalhes';
    case ENTIDADES = 'entidades';
    case PARTICIPANTES_DIRIGENTES = 'participantes_dirigentes';
    case PARTICIPANTES_EXTERNOS = 'participantes_externos';
    case TIPOS_CAMISETA = 'tipos_camiseta';
    case PRECOS = 'precos';
    case BARZINHO = 'barzinho';
    case CRONOGRAMA = 'cronograma';
    case CHECKIN = 'checkin';
    case QUARTOS = 'quartos';
    case GRUPOS = 'grupos';

    public function label(): string
    {
        return match ($this) {
            self::DETALHES => 'Detalhes',
            self::ENTIDADES => 'Entidades Envolvidas',
            self::PARTICIPANTES_DIRIGENTES => 'Participantes Dirigentes',
            self::PARTICIPANTES_EXTERNOS => 'Participantes Externos',
            self::TIPOS_CAMISETA => 'Tipos de Camiseta',
            self::PRECOS => 'Tabela de Preços',
            self::BARZINHO => 'Barzinho',
            self::CRONOGRAMA => 'Cronograma',
            self::CHECKIN => 'Check-in',
            self::QUARTOS => 'Alocação de Quartos',
            self::GRUPOS => 'Grupos de Participantes',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::DETALHES => '📋',
            self::ENTIDADES => '🏢',
            self::PARTICIPANTES_DIRIGENTES => '👥',
            self::PARTICIPANTES_EXTERNOS => '👤',
            self::TIPOS_CAMISETA => '👕',
            self::PRECOS => '💰',
            self::BARZINHO => '🍷',
            self::CRONOGRAMA => '⏰',
            self::CHECKIN => '✓',
            self::QUARTOS => '🛏️',
            self::GRUPOS => '👫',
        };
    }
}
