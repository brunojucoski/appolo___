<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposta_contrato', function (Blueprint $table) {
            if (Schema::hasColumn('proposta_contrato', 'titulo')) {
                $table->dropColumn(['titulo', 'descricao', 'data']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('proposta_contrato', function (Blueprint $table) {
            $table->string('titulo')->nullable();
            $table->string('descricao')->nullable();
            $table->dateTime('data')->nullable();
        });
    }
};
