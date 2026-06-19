<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barzinho_combo_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('combo_id')->constrained('barzinho_combos')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('barzinho_produtos')->onDelete('cascade');
            $table->integer('quantidade')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barzinho_combo_itens');
    }
};
