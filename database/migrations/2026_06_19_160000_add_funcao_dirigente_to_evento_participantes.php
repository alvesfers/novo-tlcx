<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evento_participantes', function (Blueprint $table) {
            $table->foreignId('funcao_dirigente_id')->nullable()->after('dirigente_id')->constrained('funcoes_dirigentes')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('evento_participantes', function (Blueprint $table) {
            $table->dropForeignKey(['funcao_dirigente_id']);
            $table->dropColumn('funcao_dirigente_id');
        });
    }
};
