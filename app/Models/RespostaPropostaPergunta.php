<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RespostaPropostaPergunta extends Model
{
    protected $table = 'respostas_proposta_pergunta';

    protected $fillable = [
        'id_proposta',
        'id_pergunta',
        'texto_resposta',
        'indice_opcao',
        'caminho_anexo',
    ];

    protected $casts = [
        'indice_opcao' => 'integer',
    ];

    public function proposta(): BelongsTo
    {
        return $this->belongsTo(PropostaContrato::class, 'id_proposta');
    }

    public function pergunta(): BelongsTo
    {
        return $this->belongsTo(PerguntaPropostaContrato::class, 'id_pergunta');
    }
}
