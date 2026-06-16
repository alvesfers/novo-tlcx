<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento_entidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->cascadeOnDelete();
            $table->foreignId('entidade_id')->constrained('entidades')->cascadeOnDelete();
            $table->string('tipo_participacao');
            $table->timestamps();

            $table->unique(['evento_id', 'entidade_id']);
            $table->index('evento_id');
            $table->index('entidade_id');
            $table->index('tipo_participacao');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_entidades');
    }
};
