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
        Schema::table('entidades', function (Blueprint $table) {
            // Campos para núcleos
            $table->string('paroquia')->nullable()->after('email');
            $table->string('endereco_paroquia')->nullable()->after('paroquia');
            $table->string('padre')->nullable()->after('endereco_paroquia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entidades', function (Blueprint $table) {
            $table->dropColumn(['paroquia', 'endereco_paroquia', 'padre']);
        });
    }
};
