<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barzinho_venda_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venda_id')->constrained('barzinho_vendas')->onDelete('cascade');
            $table->enum('tipo_item', ['produto', 'combo']);
            $table->foreignId('produto_id')->nullable()->constrained('barzinho_produtos')->onDelete('set null');
            $table->foreignId('combo_id')->nullable()->constrained('barzinho_combos')->onDelete('set null');
            $table->integer('quantidade');
            $table->decimal('preco_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->boolean('consignado')->default(false);
            $table->foreignId('barzinho_produto_consignado_id')->nullable()->constrained('barzinho_produtos_consignados')->onDelete('set null');
            $table->enum('comissao_tipo', ['percentual', 'valor_fixo'])->nullable();
            $table->decimal('comissao_valor', 10, 2)->nullable();
            $table->foreignId('comissao_vai_para_entidade_id')->nullable()->constrained('entidades')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barzinho_venda_itens');
    }
};
