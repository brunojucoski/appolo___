<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\PropostaContrato;
use App\Models\PortfolioArtista;

class FeedbackController extends Controller
{
 public function verificarPendentes()
{
    $usuario = auth()->user();

    if (!$usuario) return response()->json([]);

    if ($usuario->tipo_usuario == 2) {
        // Artista logado → buscar propostas vinculadas ao seu portfolio
        $propostas = PropostaContrato::whereHas('artista', function ($q) use ($usuario) {
                $q->whereHas('usuario', function ($query) use ($usuario) {
                    $query->where('id', $usuario->id);
                });
            })
            ->where('data', '<', now())
            ->where('status', 'Finalizada')
            ->whereDoesntHave('feedbackContratante', function ($q) use ($usuario) {
                $q->where('id_usuario_avaliador', $usuario->id);
            })
            ->get();
              dd($propostas); 
    }
    else if ($usuario->tipo_usuario == 3) {
        // Contratante logado → avaliar artista
        $propostas = PropostaContrato::where('id_usuario_avaliador', $usuario->id)
            ->where('data', '<', now())
            ->where('status', 'Finalizada')
            ->whereDoesntHave('feedbackArtista', function ($q) use ($usuario) {
                $q->where('id_usuario_avaliador', $usuario->id);
            })
            ->get();
    } else {
        $propostas = collect();
    }

    return response()->json($propostas);
}
}