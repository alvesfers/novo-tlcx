<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fornecedor_camiseta_tamanhos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornecedor_camiseta_tipo_id')->constrained('fornecedor_camiseta_tipos')->onDelete('cascade');
            $table->string('tamanho', 10);
            $table->json('medidas');
            $table->integer('ordem')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->unique(['fornecedor_camiseta_tipo_id', 'tamanho'], 'fct_tamanhos_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fornecedor_camiseta_tamanhos');
    }
};
