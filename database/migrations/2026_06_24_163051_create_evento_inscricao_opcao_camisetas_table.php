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
        Schema::create('evento_inscricao_opcao_camisetas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opcao_id')->constrained('evento_inscricao_opcoes')->onDelete('cascade');
            $table->enum('tipo_camiseta', ['normal', 'plus', 'babylook', 'oversized', 'infantil']);
            $table->decimal('valor_adicional', 8, 2)->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->unique(['opcao_id', 'tipo_camiseta']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_inscricao_opcao_camisetas');
    }
};
