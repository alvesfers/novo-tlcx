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
        Schema::table('dirigentes', function (Blueprint $table) {
            $table->dropColumn('respostas_formulario_evento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dirigentes', function (Blueprint $table) {
            $table->json('respostas_formulario_evento')->nullable();
        });
    }
};
