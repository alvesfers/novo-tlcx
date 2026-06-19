<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // id_casa_retiro should be on eventos, not on dirigentes
        // This migration is intentionally left empty
    }

    public function down(): void
    {
        Schema::table('dirigentes', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['id_casa_retiro_foreign']);
            $table->dropColumn('id_casa_retiro');
        });
    }
};
