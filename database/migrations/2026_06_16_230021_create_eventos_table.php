<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_evento_id')->constrained('tipo_eventos')->cascadeOnDelete();
            $table->foreignId('entidade_criadora_id')->constrained('entidades')->cascadeOnDelete();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->dateTime('data_inicio');
            $table->dateTime('data_fim')->nullable();
            $table->string('local')->nullable();
            $table->string('escopo');
            $table->string('status')->default('rascunho');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('tipo_evento_id');
            $table->index('entidade_criadora_id');
            $table->index('escopo');
            $table->index('status');
            $table->index('ativo');
            $table->index('data_inicio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
