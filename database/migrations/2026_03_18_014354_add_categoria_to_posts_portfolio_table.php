<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts_portfolio', function (Blueprint $table) {
            $table->unsignedBigInteger('id_categoria_post_portfolio')
                ->nullable()
                ->after('id_portfolio');

            // FK
            $table->foreign('id_categoria_post_portfolio')
                ->references('id')
                ->on('categorias_posts_portfolio')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('posts_portfolio', function (Blueprint $table) {
            $table->dropForeign(['id_categoria_post_portfolio']);
            $table->dropColumn('id_categoria_post_portfolio');
        });
    }
};
