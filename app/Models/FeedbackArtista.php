<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedbackArtista extends Model
{
    use SoftDeletes;


   protected $table = 'feedbacks_artistas'; 

    protected $fillable = [
        'id_artista',
        'id_usuario_avaliador',
        'id_proposta', 
        'nota',
        'comentario',
    ];

   
    public function proposta()
    {
        return $this->belongsTo(PropostaContrato::class, 'id_proposta');
    }

    public function artista()
    {
        return $this->belongsTo(Usuario::class, 'id_artista');
    }

    public function avaliador()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_avaliador');
    }
}