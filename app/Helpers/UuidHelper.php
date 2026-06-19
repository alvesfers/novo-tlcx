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
        } while (DB::table($model->getTable())->where('uuid', $uuid)->exists());

        return $uuid;
    }

    private static function generate($length = 5): string
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= self::$charset[rand(0, strlen(self::$charset) - 1)];
        }
        return $result;
    }
}
