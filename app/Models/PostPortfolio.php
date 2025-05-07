<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostPortfolio extends Model
{
    use SoftDeletes;

    protected $table = 'posts_portfolio';

    protected $fillable = [
        'id_portfolio',
        'nome',
        'descricao',
    ];


    public function portfolio()
    {
        return $this->belongsTo(PortfolioArtista::class, 'id_portfolio');
    }

    public function imagens()
    {
        return $this->hasMany(PostImagem::class, 'post_id');
    }

    
}
