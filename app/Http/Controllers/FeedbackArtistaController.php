<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\PropostaContrato; 
use App\Models\Usuario;
use App\Models\Notificacao;
use App\Models\TipoUsuario;
use App\Models\FeedbackArtista;

class FeedbackArtistaController extends Controller
{

    public function verificarPendentes()
    {
        $usuario = auth()->user();

        if (!$usuario || $usuario->tipo_usuario !== 3) {
            return response()->json([]);
        }

        \Log::info('Verificando pendentes para contratante ID: ' . $usuario->id);

        $propostas = PropostaContrato::with('artista')
            ->where('id_usuario_avaliador', $usuario->id)
            ->where('data', '<', now())
            ->where('status', 'Finalizada')
            ->get();

        \Log::info('Propostas encontradas: ' . $propostas->count());

        $pendentes = $propostas->filter(function ($proposta) use ($usuario) {
            // AQUI ESTÁ A MUDANÇA CRUCIAL: Adicionar where('id_proposta', $proposta->id)
            $temFeedback = FeedbackArtista::where('id_usuario_avaliador', $usuario->id)
                ->where('id_artista', $proposta->id_artista)
                ->where('id_proposta', $proposta->id) // <<< ADICIONE ESTA LINHA
                ->exists();

            \Log::info("Proposta ID {$proposta->id}: já tem feedback? " . ($temFeedback ? 'SIM' : 'NÃO'));

            return !$temFeedback;
        });

        return response()->json($pendentes->values());
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_proposta' => 'required|exists:proposta_contrato,id',
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:1000',
        ]);

        $proposta = PropostaContrato::find($request->id_proposta);

        // Verificação extra para evitar feedbacks duplicados para a mesma proposta
        $feedbackExistente = FeedbackArtista::where('id_proposta', $request->id_proposta)
                                            ->where('id_usuario_avaliador', Auth::id())
                                            ->exists();

        if ($feedbackExistente) {
            // Opcional: redirecionar com erro ou mensagem
            return redirect()->back()->with('error', 'Você já enviou feedback para esta proposta.');
        }

        FeedbackArtista::create([
            'id_artista' => $proposta->id_artista,
            'id_usuario_avaliador' => Auth::id(),
            'id_proposta' => $request->id_proposta, // <<< ADICIONE ESTA LINHA
            'nota' => $request->nota,
            'comentario' => $request->comentario,
        ]);

        return redirect()->back()->with('success', 'Feedback enviado com sucesso. Muito obrigado por contribuir com nossa comunidade!');
    }
}