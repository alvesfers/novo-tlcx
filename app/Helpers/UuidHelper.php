<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UuidHelper
{
    private static $charset = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function generateUnique($model, $length = 5): string
    {
        do {
            $uuid = self::generate($length);
        } while (self::uuidExists($uuid));

        return $uuid;
    }

    private static function generate($length = 5): string
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= self::$charset[rand(0, strlen(self::$charset) - 1)];
        }
        return strtoupper($result);
    }

    /**
     * Verificar se UUID existe em QUALQUER tabela relevante
     * Garante unicidade global entre dirigentes, participante_externos e eventos
     */
    private static function uuidExists(string $uuid): bool
    {
        return DB::table('dirigentes')->where('uuid', $uuid)->exists() ||
               DB::table('participante_externos')->where('uuid', $uuid)->exists() ||
               DB::table('eventos')->where('uuid', $uuid)->exists();
    }
}
