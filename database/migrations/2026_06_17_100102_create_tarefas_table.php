<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entidade_id')->constrained('entidades')->onDelete('cascade');
            $table->foreignId('evento_id')->nullable()->constrained('eventos')->onDelete('set null');
            $table->foreignId('tarefa_categoria_id')->nullable()->constrained('tarefa_categorias')->onDelete('set null');
            $table->foreignId('responsavel_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('responsavel_dirigente_id')->nullable()->constrained('dirigentes')->onDelete('set null');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->string('status')->default('pendente');
            $table->string('prioridade')->default('media');
            $table->date('data_inicio')->nullable();
            $table->date('data_limite')->nullable();
            $table->dateTime('concluida_em')->nullable();
            $table->foreignId('criada_por_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('observacao')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entidade_id');
            $table->index('evento_id');
            $table->index('tarefa_categoria_id');
            $table->index('responsavel_user_id');
            $table->index('responsavel_dirigente_id');
            $table->index('status');
            $table->index('prioridade');
            $table->index('data_limite');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarefas');
    }
};
