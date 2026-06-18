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
        Schema::create('alas_casas_retiro', function (Blueprint $table) {
            $table->id('id_ala');
            $table->unsignedBigInteger('id_casa');
            $table->string('nome_ala');
            $table->string('descricao')->nullable();
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
        Schema::dropIfExists('alas_casas_retiro');
    }
};
