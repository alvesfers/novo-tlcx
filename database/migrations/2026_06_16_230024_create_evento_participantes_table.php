<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento_participantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('eventos')->cascadeOnDelete();
            $table->string('tipo_participante');
            $table->foreignId('dirigente_id')->nullable()->constrained('dirigentes')->cascadeOnDelete();
            $table->foreignId('participante_externo_id')->nullable()->constrained('participante_externos')->cascadeOnDelete();
            $table->boolean('presenca')->default(false);
            $table->dateTime('checkin_em')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();

            $table->index('evento_id');
            $table->index('tipo_participante');
            $table->index('dirigente_id');
            $table->index('participante_externo_id');
            $table->index('presenca');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_participantes');
    }
};
