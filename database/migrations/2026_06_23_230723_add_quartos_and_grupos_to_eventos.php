<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->json('quartos')->nullable()->comment('Alocação de participantes em quartos: {ala_id: {quarto_id: [participante_ids]}}');
            $table->json('grupos')->nullable()->comment('Grupos de participantes externos com dirigentes responsáveis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn('quartos');
            $table->dropColumn('grupos');
        });
    }
};
