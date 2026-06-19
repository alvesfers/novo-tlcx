<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barzinho_vendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barzinho_id')->constrained('barzinhos')->onDelete('cascade');
            $table->foreignId('evento_participante_id')->constrained('evento_participantes')->onDelete('cascade');
            $table->foreignId('forma_pagamento_id')->nullable()->constrained('formas_pagamento')->onDelete('set null');
            $table->enum('tipo_pagamento', ['dinheiro', 'credito', 'debito', 'pix'])->nullable();
            $table->string('descricao', 255)->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('desconto', 10, 2)->default(0);
            $table->decimal('taxa_pagamento', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->enum('status_pagamento', ['pendente', 'pago', 'cancelado'])->default('pendente');
            $table->dateTime('data_pagamento')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barzinho_vendas');
    }
};
