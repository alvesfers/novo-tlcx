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
        Schema::table('eventos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_casa')->nullable();
            $table->foreign('id_casa')->references('id_casa')->on('casas_de_retiro')->onDelete('set null');

            $table->foreignId('fornecedores_camisetas_id')->nullable()->constrained('fornecedores_camisetas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['id_casa']);
            $table->dropForeignKeyIfExists(['fornecedores_camisetas_id']);
            $table->dropColumn('id_casa');
            $table->dropColumn('fornecedores_camisetas_id');
        });
    }
};
