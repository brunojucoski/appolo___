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
            $table->string('cor_primaria_portfolio', 7)->nullable()->after('link_behance');
            $table->string('cor_secundaria_portfolio', 7)->nullable()->after('cor_primaria_portfolio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portfolio_artistas', function (Blueprint $table) {
            $table->dropColumn([
                'cor_primaria_portfolio',
                'cor_secundaria_portfolio',
            ]);
        });
    }
};
