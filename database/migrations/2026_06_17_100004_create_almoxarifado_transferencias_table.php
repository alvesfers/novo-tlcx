<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('almoxarifado_transferencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entidade_origem_id')->constrained('entidades')->onDelete('cascade');
            $table->foreignId('entidade_destino_id')->constrained('entidades')->onDelete('cascade');
            $table->foreignId('almoxarifado_item_id')->constrained('almoxarifado_itens')->onDelete('cascade');
            $table->decimal('quantidade', 10, 2);
            $table->string('status')->default('pendente');
            $table->foreignId('solicitado_por_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('aprovado_por_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('data_solicitacao');
            $table->dateTime('data_conclusao')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('entidade_origem_id');
            $table->index('entidade_destino_id');
            $table->index('almoxarifado_item_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('almoxarifado_transferencias');
    }
};
