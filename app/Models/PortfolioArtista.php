<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortfolioArtista extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'portfolio_artistas';

    protected $fillable = [
        'id_usuario',
        'nome_artistico',
        'descricao',
        'link_instagram',
        'link_behance',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }


    public function posts()
{
    return $this->hasMany(PostPortfolio::class, 'id_portfolio')->orderBy('created_at', 'desc');
}

public function feedbacksRecebidos()
{
    return $this->hasMany(\App\Models\FeedbackArtista::class, 'id_artista');
}
}