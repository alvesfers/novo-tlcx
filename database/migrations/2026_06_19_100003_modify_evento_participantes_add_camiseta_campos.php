<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evento_participantes', function (Blueprint $table) {
            $table->foreignId('dirigente_funcao_id')->nullable()->constrained('dirigente_funcoes')->onDelete('set null');
            $table->string('tipo_camiseta', 50)->nullable();
            $table->string('tamanho_camiseta', 10)->nullable();
            $table->json('respostas_formulario')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('evento_participantes', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['dirigente_funcao_id_foreign']);
            $table->dropColumn(['dirigente_funcao_id', 'tipo_camiseta', 'tamanho_camiseta', 'respostas_formulario']);
        });
    }
};
