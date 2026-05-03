<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimelinePropostaContrato extends Model
{
    public const TIPO_MENSAGEM = 'mensagem';

    public const TIPO_EVENTO = 'evento_sistema';

    public const EVT_ENVIO = 'envio_proposta';

    public const EVT_ACEITE = 'aceite_proposta';

    public const EVT_RECUSA = 'recusa_proposta';

    public const EVT_FINALIZADA = 'finalizada_proposta';

    protected $table = 'timeline_proposta_contrato';

    protected $fillable = [
        'id_proposta',
        'id_usuario',
        'tipo',
        'codigo_evento',
        'texto',
    ];

    public function proposta(): BelongsTo
    {
        return $this->belongsTo(PropostaContrato::class, 'id_proposta');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function linhaDescritiva(): string
    {
        $when = $this->created_at->format('d/m/Y H:i');
        $nome = $this->usuario?->nome ?? 'Usuário';

        if ($this->tipo === self::TIPO_MENSAGEM) {
            return "{$when} — \"{$nome}\": {$this->texto}";
        }

        $quem = $this->usuario
            ? "\"{$nome}\""
            : 'Um visitante (sem cadastro)';

        return match ($this->codigo_evento) {
            self::EVT_ENVIO => "{$when} — {$quem} enviou uma proposta de orçamento.",
            self::EVT_ACEITE => "{$when} — \"{$nome}\" aceitou a proposta.".($this->texto ? ' Observações: '.$this->texto : ''),
            self::EVT_RECUSA => "{$when} — \"{$nome}\" recusou a proposta.".($this->texto ? ' Motivo: '.$this->texto : ''),
            self::EVT_FINALIZADA => "{$when} — \"{$nome}\" marcou a proposta como concluída.",
            default => "{$when} — \"{$nome}\"",
        };
    }

}
