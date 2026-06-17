<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entidade_id')->constrained('entidades')->onDelete('cascade');
            $table->foreignId('evento_id')->nullable()->constrained('eventos')->onDelete('set null');
            $table->foreignId('documento_categoria_id')->nullable()->constrained('documento_categorias')->onDelete('set null');
            $table->foreignId('uploaded_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('arquivo_nome_original');
            $table->string('arquivo_nome_armazenado');
            $table->string('arquivo_path');
            $table->string('arquivo_mime');
            $table->bigInteger('arquivo_tamanho');
            $table->string('tipo_documento')->default('geral');
            $table->string('visibilidade')->default('privado');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('entidade_id');
            $table->index('evento_id');
            $table->index('documento_categoria_id');
            $table->index('uploaded_by_user_id');
            $table->index('tipo_documento');
            $table->index('visibilidade');
            $table->index('ativo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
