<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dirigentes', function (Blueprint $table) {
            $table->string('apelido')->nullable()->after('nome');
            $table->unsignedBigInteger('id_mini_tlc')->nullable()->after('apelido');
            $table->foreign('id_mini_tlc')->references('id')->on('eventos')->onDelete('set null');
        });

        DB::statement("ALTER TABLE dirigente_entidades MODIFY COLUMN tipo_vinculo ENUM('principal', 'adicional', 'coordenacao', 'mini_dirigente') NOT NULL");
    }

    public function down(): void
    {
        Schema::table('dirigentes', function (Blueprint $table) {
            $table->dropForeign(['id_mini_tlc']);
            $table->dropColumn(['apelido', 'id_mini_tlc']);
        });

        DB::statement("ALTER TABLE dirigente_entidades MODIFY COLUMN tipo_vinculo ENUM('principal', 'adicional', 'coordenacao') NOT NULL");
    }
};
