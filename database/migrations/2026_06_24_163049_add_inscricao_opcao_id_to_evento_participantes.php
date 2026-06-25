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
        Schema::table('evento_participantes', function (Blueprint $table) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('evento_participantes', 'inscricao_camiseta_tipo')) {
                $table->string('inscricao_camiseta_tipo', 20)->nullable()->after('inscricao_opcao_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('evento_participantes', function (Blueprint $table) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('evento_participantes', 'inscricao_camiseta_tipo')) {
                $table->dropColumn('inscricao_camiseta_tipo');
            }
        });
    }
};
