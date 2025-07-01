<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedbackContratante extends Model
{
    use SoftDeletes;

     protected $table = 'feedbacks_contratantes'; 
    
     protected $fillable = [
        'id_proposta',
        'id_usuario',
        'id_usuario_avaliador',
        'nota',
        'comentario',
    ];

        public function contratante()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

   
    public function avaliador()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario_avaliador');
    }

    public function proposta()
    {
        return $this->belongsTo(PropostaContrato::class, 'id_proposta');
    }
}
