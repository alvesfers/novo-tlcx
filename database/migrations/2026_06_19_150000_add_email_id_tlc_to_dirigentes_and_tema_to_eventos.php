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
        // Adicionar campos em dirigentes
        Schema::table('dirigentes', function (Blueprint $table) {
            $table->string('email')->nullable()->after('telefone');
            $table->unsignedBigInteger('id_tlc')->nullable()->after('email');
            $table->foreign('id_tlc')->references('id')->on('eventos')->onDelete('set null');
        });

        // Adicionar campo tema em eventos
        Schema::table('eventos', function (Blueprint $table) {
            $table->string('tema')->nullable()->after('descricao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dirigentes', function (Blueprint $table) {
            $table->dropForeign(['id_tlc']);
            $table->dropColumn(['id_tlc', 'email']);
        });

        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn('tema');
        });
    }
};
