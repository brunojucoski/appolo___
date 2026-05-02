<?php

namespace App\Http\Controllers;

use App\Models\FeedbackContratante;
use App\Models\PropostaContrato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackContratanteController extends Controller
{
    /**
     * Artista (tipo 2): propostas finalizadas em que ainda não avaliou o solicitante.
     */
    public function verificarPendentes()
    {
        $usuario = auth()->user();

        if (! $usuario || (int) $usuario->tipo_usuario !== 2) {
            return response()->json([]);
        }

        $portfolio = $usuario->portfolioArtista;
        if (! $portfolio) {
            return response()->json([]);
        }

        $propostas = PropostaContrato::with(['usuarioAvaliador', 'artista'])
            ->where('id_artista', $portfolio->id)
            ->where('status', 'Finalizada')
            ->whereDoesntHave('feedbackContratante')
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

        FeedbackContratante::create([
            'id_proposta' => $proposta->id,
            'id_usuario' => $proposta->id_usuario_avaliador,
            'id_usuario_avaliador' => Auth::id(),
            'nota' => $request->nota,
            'comentario' => $request->comentario,
        ]);

        return redirect()->back()->with('success', 'Feedback enviado com sucesso.');
    }
}
