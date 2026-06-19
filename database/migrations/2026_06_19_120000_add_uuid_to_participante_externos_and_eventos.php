<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('participante_externos', 'uuid')) {
            Schema::table('participante_externos', function (Blueprint $table) {
                $table->string('uuid')->nullable()->unique()->after('id');
            });
        }

        if (!Schema::hasColumn('eventos', 'uuid')) {
            Schema::table('eventos', function (Blueprint $table) {
                $table->string('uuid')->nullable()->unique()->after('id');
            });
        }

        if (!Schema::hasColumn('eventos', 'qr_code')) {
            Schema::table('eventos', function (Blueprint $table) {
                $table->longText('qr_code')->nullable()->after('uuid');
            });
        }
    }

    public function down(): void
    {
        Schema::table('participante_externos', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });

        Schema::table('eventos', function (Blueprint $table) {
            $table->dropColumn('uuid');
            $table->dropColumn('qr_code');
        });
    }
};
