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
        Schema::create('dirigentes', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('qr_code')->nullable();
            $table->string('nome');
            $table->string('telefone')->nullable();
            $table->string('genero')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('foto_url')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dirigentes');
    }
};
