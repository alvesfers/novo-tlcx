<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barzinhos', function (Blueprint $table) {
            // Garantir que cada evento tem no máximo 1 barzinho
            $table->unique('evento_id');

            // Tipo de venda do barzinho
            $table->enum('tipo_venda', ['consumo_depois', 'paga_na_hora', 'pre_pago'])
                ->default('consumo_depois')
                ->after('ativo');

            // Configurações específicas do tipo de venda
            $table->json('barzinho_config')->nullable()->after('tipo_venda');
        });
    }

    public function down(): void
    {
        Schema::table('barzinhos', function (Blueprint $table) {
            $table->dropUnique(['evento_id']);
            $table->dropColumn(['tipo_venda', 'barzinho_config']);
        });
    }
};
