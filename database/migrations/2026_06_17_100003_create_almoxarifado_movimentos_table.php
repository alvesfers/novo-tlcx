<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('almoxarifado_movimentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entidade_id')->constrained('entidades')->onDelete('cascade');
            $table->foreignId('almoxarifado_item_id')->constrained('almoxarifado_itens')->onDelete('cascade');
            $table->foreignId('evento_id')->nullable()->constrained('eventos')->onDelete('set null');
            $table->string('tipo_movimento');
            $table->decimal('quantidade', 10, 2);
            $table->decimal('quantidade_anterior', 10, 2);
            $table->decimal('quantidade_posterior', 10, 2);
            $table->text('descricao')->nullable();
            $table->foreignId('responsavel_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('data_movimento');
            $table->text('observacao')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entidade_id');
            $table->index('almoxarifado_item_id');
            $table->index('evento_id');
            $table->index('tipo_movimento');
            $table->index('data_movimento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('almoxarifado_movimentos');
    }
};
