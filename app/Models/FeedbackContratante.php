<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedbackContratante extends Model
{
    use SoftDeletes;

     protected $table = 'feedbacks_contratantes'; 

    protected $fillable = [
        'id_usuario',
        'id_usuario_avaliador',
        'nota',
        'comentario',
    ];
}
