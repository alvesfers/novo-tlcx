<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fornecedor_camiseta_tipos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornecedor_id')->constrained('fornecedores_camisetas')->onDelete('cascade');
            $table->enum('tipo_camiseta', ['infantil', 'normal', 'plus', 'babylook', 'oversized']);
            $table->integer('ordem')->default(0);
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->unique(['fornecedor_id', 'tipo_camiseta']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fornecedor_camiseta_tipos');
    }
};
