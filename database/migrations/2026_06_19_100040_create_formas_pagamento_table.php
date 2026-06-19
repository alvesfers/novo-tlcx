<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formas_pagamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entidade_id')->constrained('entidades')->onDelete('cascade');
            $table->string('nome', 255);
            $table->enum('tipo', ['dinheiro', 'cartao_credito', 'cartao_debito', 'pix', 'outra']);
            $table->decimal('taxa_credito', 5, 2)->default(0);
            $table->decimal('taxa_debito', 5, 2)->default(0);
            $table->decimal('taxa_pix', 5, 2)->default(0);
            $table->boolean('ativa')->default(true);
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formas_pagamento');
    }
};
