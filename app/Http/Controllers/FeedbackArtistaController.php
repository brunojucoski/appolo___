<?php

namespace App\Http\Controllers;

use App\Models\FeedbackArtista;
use App\Models\PropostaContrato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackArtistaController extends Controller
{
    /**
     * Solicitante (tipo 3): propostas finalizadas em que ainda não avaliou o artista.
     */
    public function verificarPendentes()
    {
        $usuario = auth()->user();

        if (! $usuario || (int) $usuario->tipo_usuario !== 3) {
            return response()->json([]);
        }

        $propostas = PropostaContrato::with(['artista', 'usuarioAvaliador'])
            ->where('id_usuario_avaliador', $usuario->id)
            ->where('status', 'Finalizada')
            ->whereDoesntHave('feedbackArtistaNaProposta')
            ->get();

        return response()->json($propostas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_proposta' => 'required|exists:proposta_contrato,id',
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:1000',
        ]);

        $proposta = PropostaContrato::find($request->id_proposta);

        $feedbackExistente = FeedbackArtista::where('id_proposta', $request->id_proposta)
            ->where('id_usuario_avaliador', Auth::id())
            ->exists();

        if ($feedbackExistente) {
            return redirect()->back()->with('error', 'Você já enviou feedback para esta proposta.');
        }

        FeedbackArtista::create([
            'id_artista' => $proposta->id_artista,
            'id_usuario_avaliador' => Auth::id(),
            'id_proposta' => $request->id_proposta,
            'nota' => $request->nota,
            'comentario' => $request->comentario,
        ]);

        return redirect()->back()->with('success', 'Feedback enviado com sucesso. Muito obrigado por contribuir com nossa comunidade!');
    }
}
