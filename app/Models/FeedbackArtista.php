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
        'nota',
        'comentario',
    ];
}
