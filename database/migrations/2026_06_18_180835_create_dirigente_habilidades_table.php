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
        Schema::create('dirigente_habilidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dirigente_id')->constrained('dirigentes')->cascadeOnDelete();
            $table->foreignId('habilidade_id')->constrained('habilidades')->cascadeOnDelete();
            $table->string('nivel');
            $table->text('observacao')->nullable();
            $table->timestamps();
            $table->unique(['dirigente_id', 'habilidade_id']);
            $table->index(['dirigente_id', 'habilidade_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dirigente_habilidades');
    }
};
