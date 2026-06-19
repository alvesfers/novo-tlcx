<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barzinho_produtos_consignados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barzinho_produto_id');
            $table->unsignedBigInteger('almoxarifado_item_id');
            $table->unsignedBigInteger('comissao_vai_para_entidade_id');
            $table->enum('tipo_comissao', ['percentual', 'valor_fixo']);
            $table->decimal('comissao_valor', 10, 2);
            $table->decimal('preco_custo_original', 10, 2)->nullable();
            $table->dateTime('data_consignacao')->nullable();
            $table->boolean('ativa')->default(true);
            $table->timestamps();

            $table->foreign('barzinho_produto_id', 'fk_bpc_barzprod_id')
                ->references('id')
                ->on('barzinho_produtos')
                ->onDelete('cascade');

            $table->foreign('almoxarifado_item_id', 'fk_bpc_almx_id')
                ->references('id')
                ->on('almoxarifado_itens')
                ->onDelete('restrict');

            $table->foreign('comissao_vai_para_entidade_id', 'fk_bpc_entd_id')
                ->references('id')
                ->on('entidades')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barzinho_produtos_consignados');
    }
};
