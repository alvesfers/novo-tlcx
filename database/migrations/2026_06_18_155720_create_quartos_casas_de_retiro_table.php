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
        Schema::create('quartos_casas_de_retiro', function (Blueprint $table) {
            $table->id('id_quarto');
            $table->unsignedBigInteger('id_casa');
            $table->string('numero_quarto');
            $table->integer('vagas')->default(0);
            $table->integer('cama')->nullable();
            $table->integer('beliche')->nullable();
            $table->integer('banheiros')->nullable();
            $table->integer('chuveiros')->nullable();
            $table->boolean('acessibilidade')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_casa')->references('id_casa')->on('casas_de_retiro')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quartos_casas_de_retiro');
    }
};
