<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            // Cronograma de atividades
            $table->json('cronograma')->nullable()->after('tema');

            // Quais módulos estão habilitados neste evento
            $table->json('modulos_habilitados')->nullable()->after('cronograma');
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn(['cronograma', 'modulos_habilitados']);
        });
    }
};
