<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento_valores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->onDelete('cascade');
            $table->string('tipo_valor', 100);
            $table->decimal('valor', 10, 2);
            $table->string('descricao', 255)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->unique(['evento_id', 'tipo_valor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_valores');
    }
};
