<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropostaContrato extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'proposta_contrato';

    protected $fillable = [
        'id_artista',
        'id_usuario_avaliador',
        'titulo',
        'descricao',
        'data',
        'status',
        'motivo',
    ];

    protected $dates = ['data', 'deleted_at'];

    public function artista()
    {
        return $this->belongsTo(PortfolioArtista::class, 'id_artista');
    }

    public function usuarioAvaliador()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_avaliador');
    }

    
    // Feedback dado ao artista (por contratante)
public function feedbackArtista()
{
    return $this->hasOne(FeedbackArtista::class, 'id_usuario_avaliador', 'id_usuario_avaliador')
                ->whereColumn('id_artista', 'id_artista');
}

// Feedback dado ao contratante (por artista)
public function feedbackContratante()
{
    return $this->hasOne(FeedbackContratante::class, 'id_usuario_avaliador', 'id_usuario_avaliador')
                ->whereHas('proposta', function ($q) {
                    $q->whereColumn('id_usuario_avaliador', 'id_usuario_avaliador');
                });
}

}
