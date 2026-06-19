<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento_tipos_camiseta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->foreignId('fornecedor_id')->constrained('fornecedores_camisetas')->onDelete('restrict');
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->unique(['evento_id', 'fornecedor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_tipos_camiseta');
    }
};
