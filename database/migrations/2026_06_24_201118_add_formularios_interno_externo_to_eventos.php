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
            $table->json('formulario_dirigentes_interno')->nullable()->after('formulario_dirigentes');
            $table->json('formulario_dirigentes_externo')->nullable()->after('formulario_dirigentes_interno');
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn(['formulario_dirigentes_interno', 'formulario_dirigentes_externo']);
        });
    }
};
