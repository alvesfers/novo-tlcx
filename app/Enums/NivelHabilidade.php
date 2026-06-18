<?php

namespace App\Enums;

enum NivelHabilidade: string
{
    case Iniciante = 'iniciante';
    case Basico = 'basico';
    case Intermediario = 'intermediario';
    case Experiente = 'experiente';
    case Profissional = 'profissional';

    public function label(): string
    {
        return match ($this) {
            self::Iniciante => 'Iniciante',
            self::Basico => 'Básico',
            self::Intermediario => 'Intermediário',
            self::Experiente => 'Experiente',
            self::Profissional => 'Profissional',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Iniciante => 'blue',
            self::Basico => 'cyan',
            self::Intermediario => 'yellow',
            self::Experiente => 'orange',
            self::Profissional => 'red',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
