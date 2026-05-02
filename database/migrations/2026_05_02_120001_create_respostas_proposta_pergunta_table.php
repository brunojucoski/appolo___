<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respostas_proposta_pergunta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_proposta')
                ->constrained('proposta_contrato')
                ->cascadeOnDelete();
            $table->foreignId('id_pergunta')
                ->constrained('perguntas_proposta_contrato')
                ->restrictOnDelete();
            $table->text('texto_resposta')->nullable();
            $table->unsignedTinyInteger('indice_opcao')->nullable();
            $table->string('caminho_anexo')->nullable();
            $table->timestamps();

            $table->unique(['id_proposta', 'id_pergunta']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respostas_proposta_pergunta');
    }
};
