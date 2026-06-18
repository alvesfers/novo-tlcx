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
        Schema::create('casas_de_retiro', function (Blueprint $table) {
            $table->id('id_casa');
            $table->string('nome_casa');
            $table->string('endereco');
            $table->decimal('valor_estimado', 10, 2)->nullable();
            $table->boolean('acessibilidade')->default(false);
            $table->boolean('ativa')->default(true);
            $table->integer('capacidade')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casas_de_retiro');
    }
};
