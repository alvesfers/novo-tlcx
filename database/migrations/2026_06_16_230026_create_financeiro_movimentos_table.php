<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financeiro_movimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entidade_id')->constrained('entidades')->onDelete('cascade');
            $table->foreignId('financeiro_categoria_id')->constrained('financeiro_categorias')->onDelete('restrict');
            $table->foreignId('evento_id')->nullable()->constrained('eventos')->onDelete('set null');
            $table->enum('tipo', ['entrada', 'saida']);
            $table->string('descricao');
            $table->decimal('valor', 10, 2);
            $table->date('data_movimento');
            $table->enum('forma_pagamento', ['dinheiro', 'pix', 'transferencia', 'cartao', 'cheque', 'outro']);
            $table->string('comprovante_url')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['entidade_id', 'tipo']);
            $table->index('data_movimento');
            $table->index('evento_id');
            $table->index(['entidade_id', 'data_movimento']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financeiro_movimentos');
    }
};
