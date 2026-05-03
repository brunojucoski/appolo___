<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposta_contrato', function (Blueprint $table) {
            $table->dropForeign(['id_usuario_avaliador']);
        });

        DB::statement('ALTER TABLE proposta_contrato MODIFY id_usuario_avaliador BIGINT UNSIGNED NULL');

        Schema::table('proposta_contrato', function (Blueprint $table) {
            $table->foreign('id_usuario_avaliador')
                ->references('id')
                ->on('usuarios')
                ->cascadeOnDelete();
        });

        Schema::table('timeline_proposta_contrato', function (Blueprint $table) {
            $table->dropForeign(['id_usuario']);
        });

        DB::statement('ALTER TABLE timeline_proposta_contrato MODIFY id_usuario BIGINT UNSIGNED NULL');

        Schema::table('timeline_proposta_contrato', function (Blueprint $table) {
            $table->foreign('id_usuario')
                ->references('id')
                ->on('usuarios')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        DB::table('proposta_contrato')->whereNull('id_usuario_avaliador')->delete();

        Schema::table('timeline_proposta_contrato', function (Blueprint $table) {
            $table->dropForeign(['id_usuario']);
        });

        DB::statement('ALTER TABLE timeline_proposta_contrato MODIFY id_usuario BIGINT UNSIGNED NOT NULL');

        Schema::table('timeline_proposta_contrato', function (Blueprint $table) {
            $table->foreign('id_usuario')
                ->references('id')
                ->on('usuarios')
                ->cascadeOnDelete();
        });

        Schema::table('proposta_contrato', function (Blueprint $table) {
            $table->dropForeign(['id_usuario_avaliador']);
        });

        DB::statement('ALTER TABLE proposta_contrato MODIFY id_usuario_avaliador BIGINT UNSIGNED NOT NULL');

        Schema::table('proposta_contrato', function (Blueprint $table) {
            $table->foreign('id_usuario_avaliador')
                ->references('id')
                ->on('usuarios')
                ->cascadeOnDelete();
        });
    }
};
