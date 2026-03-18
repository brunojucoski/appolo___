<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias_posts_portfolio', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('id_portfolio_artista');
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->integer('ordem')->default(0);

            $table->timestamps();

            // FK
            $table->foreign('id_portfolio_artista')
                ->references('id')
                ->on('portfolio_artistas')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias_posts_portfolio');
    }
};
