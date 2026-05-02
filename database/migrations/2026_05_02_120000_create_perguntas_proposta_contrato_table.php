<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perguntas_proposta_contrato', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_portfolio_artista')
                ->constrained('portfolio_artistas')
                ->cascadeOnDelete();
            $table->string('tipo', 20); // texto | opcoes | anexo
            $table->string('titulo', 500);
            $table->json('opcoes_json')->nullable();
            $table->unsignedInteger('ordem')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perguntas_proposta_contrato');
    }
};
