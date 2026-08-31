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
        Schema::table('portfolio_artistas', function (Blueprint $table) {
            $table->unsignedTinyInteger('estilo_card_categorias_portfolio')
                ->default(1)
                ->after('cor_secundaria_portfolio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolio_artistas', function (Blueprint $table) {
            $table->dropColumn('estilo_card_categorias_portfolio');
        });
    }
};
