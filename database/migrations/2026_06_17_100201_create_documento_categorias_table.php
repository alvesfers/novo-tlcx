<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documento_categorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entidade_id')->constrained('entidades')->onDelete('cascade');
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('entidade_id');
            $table->index('ativo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documento_categorias');
    }
};
