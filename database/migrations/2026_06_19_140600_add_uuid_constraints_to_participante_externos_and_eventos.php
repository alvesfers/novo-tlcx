<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Popular UUIDs em participante_externos se estiverem vazios ou NULL
        DB::table('participante_externos')
            ->where(function ($query) {
                $query->whereNull('uuid')
                      ->orWhere('uuid', '');
            })
            ->orderBy('id')
            ->each(function ($participante) {
                $uuid = $this->generateUniqueUuid();
                DB::table('participante_externos')
                    ->where('id', $participante->id)
                    ->update(['uuid' => $uuid]);
            });

        // Popular UUIDs em eventos se estiverem vazios ou NULL
        DB::table('eventos')
            ->where(function ($query) {
                $query->whereNull('uuid')
                      ->orWhere('uuid', '');
            })
            ->orderBy('id')
            ->each(function ($evento) {
                $uuid = $this->generateUniqueUuid();
                DB::table('eventos')
                    ->where('id', $evento->id)
                    ->update(['uuid' => $uuid]);
            });

        // Adicionar constraints unique
        DB::statement('ALTER TABLE `participante_externos` ADD UNIQUE KEY IF NOT EXISTS `participante_externos_uuid_unique` (`uuid`)');
        DB::statement('ALTER TABLE `eventos` ADD UNIQUE KEY IF NOT EXISTS `eventos_uuid_unique` (`uuid`)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participante_externos', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
        });

        Schema::table('eventos', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
        });
    }

    private function generateUniqueUuid(): string
    {
        $charset = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        do {
            $uuid = '';
            for ($i = 0; $i < 5; $i++) {
                $uuid .= $charset[rand(0, strlen($charset) - 1)];
            }
        } while (
            DB::table('dirigentes')->where('uuid', $uuid)->exists() ||
            DB::table('participante_externos')->where('uuid', $uuid)->exists() ||
            DB::table('eventos')->where('uuid', $uuid)->exists()
        );
        return strtoupper($uuid);
    }
};
