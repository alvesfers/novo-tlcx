<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('entidade_pai_id')->nullable()->constrained('entidades')->nullOnDelete();
            $table->enum('tipo_entidade', ['diocese', 'nucleo', 'secretaria']);
            $table->string('nome');
            $table->string('email')->nullable();
            $table->enum('tipo_secretaria', ['aberta', 'fechada'])->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('user_id');
            $table->index('entidade_pai_id');
            $table->index('tipo_entidade');
            $table->index('ativo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entidades');
    }
};
