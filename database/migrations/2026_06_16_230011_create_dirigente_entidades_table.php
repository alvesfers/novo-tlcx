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
        Schema::create('dirigente_entidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dirigente_id')->constrained('dirigentes')->onDelete('cascade');
            $table->foreignId('entidade_id')->constrained('entidades')->onDelete('cascade');
            $table->enum('tipo_vinculo', ['principal', 'adicional', 'coordenacao']);
            $table->enum('cargo', ['dirigente', 'coordenador']);
            $table->string('papel')->nullable();
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('dirigente_id');
            $table->index('entidade_id');
            $table->index('tipo_vinculo');
            $table->index('cargo');
            $table->index('ativo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dirigente_entidades');
    }
};
