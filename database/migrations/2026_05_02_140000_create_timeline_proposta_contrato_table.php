<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timeline_proposta_contrato', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_proposta')
                ->constrained('proposta_contrato')
                ->cascadeOnDelete();
            $table->foreignId('id_usuario')
                ->constrained('usuarios')
                ->cascadeOnDelete();
            $table->string('tipo', 30); // mensagem | evento_sistema
            $table->string('codigo_evento', 40)->nullable();
            $table->text('texto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timeline_proposta_contrato');
    }
};
