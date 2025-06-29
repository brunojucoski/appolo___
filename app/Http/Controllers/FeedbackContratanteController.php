<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\PropostaContrato; 
use App\Models\Usuario;
use App\Models\Notificacao;
use App\Models\TipoUsuario;
use App\Models\FeedbackContratante;

class FeedbackContratanteController extends Controller

{


    public function verificarPendentes()
{
    $usuario = auth()->user();

    if (!$usuario) return response()->json([]);

    if ($usuario->tipo_usuario == 2) {
        $propostas = \App\Models\PropostaContrato::whereHas('portfolio', function ($q) use ($usuario) {
            $q->where('id_usuario', $usuario->id);
        })
        ->where('data', '<', now())
        ->where('status', 'Finalizada')
        ->whereDoesntHave('feedbackArtista', function ($q) use ($usuario) {
            $q->where('id_usuario_avaliador', $usuario->id);
        })
        ->get();
    } else if ($usuario->tipo_usuario == 3) {
        $propostas = \App\Models\PropostaContrato::where('id_usuario_avaliador', $usuario->id)
            ->where('data', '<', now())
            ->where('status', 'Finalizada')
            ->whereDoesntHave('feedbackContratante', function ($q) use ($usuario) {
                $q->where('id_usuario_avaliador', $usuario->id);
            })
            ->get();
    } else {
        $propostas = collect();
    }

    return response()->json($propostas);
}




    public function store(Request $request)
    {
        $request->validate([
            'id_proposta' => 'required|exists:propostas_contratos,id',
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:1000',
        ]);

        $proposta = PropostaContrato::find($request->id_proposta);

        FeedbackContratante::create([
            'id_usuario' => $proposta->id_usuario_avaliador,
            'id_usuario_avaliador' => Auth::id(),
            'nota' => $request->nota,
            'comentario' => $request->comentario,
        ]);

        return redirect()->back()->with('success', 'Feedback enviado com sucesso.');
    }


    //
}
