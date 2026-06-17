<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('almoxarifado_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entidade_id')->constrained('entidades')->onDelete('cascade');
            $table->foreignId('almoxarifado_categoria_id')->nullable()->constrained('almoxarifado_categorias')->onDelete('set null');
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->string('unidade_medida')->default('unidade');
            $table->decimal('quantidade_atual', 10, 2)->default(0);
            $table->decimal('quantidade_minima', 10, 2)->nullable();
            $table->string('localizacao')->nullable();
            $table->string('status')->default('ativo');
            $table->text('observacao')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entidade_id');
            $table->index('almoxarifado_categoria_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('almoxarifado_itens');
    }
};
