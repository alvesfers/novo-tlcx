<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento_participante_camiseta_medidas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evento_participante_id');
            $table->unsignedBigInteger('fornecedor_camiseta_tamanho_id');
            $table->json('medidas_snapshot');
            $table->timestamps();

            $table->foreign('evento_participante_id', 'fk_epcd_evento_part_id')
                ->references('id')
                ->on('evento_participantes')
                ->onDelete('cascade');

            $table->foreign('fornecedor_camiseta_tamanho_id', 'fk_epcd_forn_tam_id')
                ->references('id')
                ->on('fornecedor_camiseta_tamanhos')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_participante_camiseta_medidas');
    }
};
