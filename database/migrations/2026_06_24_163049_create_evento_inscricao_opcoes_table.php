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
        Schema::create('evento_inscricao_opcoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_id')->constrained('evento_tipos_inscricao')->onDelete('cascade');
            $table->string('nome', 150);
            $table->decimal('valor_base', 8, 2)->default(0);
            $table->text('descricao')->nullable();
            $table->boolean('inclui_camiseta')->default(false);
            $table->boolean('ativo')->default(true);
            $table->integer('ordem')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_inscricao_opcoes');
    }
};
