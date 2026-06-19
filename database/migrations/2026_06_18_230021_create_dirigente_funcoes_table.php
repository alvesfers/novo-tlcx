<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dirigente_funcoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dirigente_id')->constrained('dirigentes')->onDelete('cascade');
            $table->foreignId('funcao_dirigente_id')->constrained('funcoes_dirigentes')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['dirigente_id', 'funcao_dirigente_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dirigente_funcoes');
    }
};
