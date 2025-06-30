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

    // Garante que apenas contratantes (tipo_usuario == 3) acessem
    if (!$usuario || $usuario->tipo_usuario !== 3) {
        return response()->json([]);
    }

    $propostas = \App\Models\PropostaContrato::where('id_usuario_avaliador', $usuario->id)
        ->where('data', '<', now())
        ->where('status', 'Finalizada')
        ->whereDoesntHave('feedbackArtista', function ($q) use ($usuario) {
            $q->where('id_usuario_avaliador', $usuario->id);
        })
        ->get();

    return response()->json($propostas);
}

//  public function pendentes()
//     {
//         $user = Auth::user();

//         if ($user->tipo_usuario != 3) {
//             return response()->json([]);
//         }

//         $propostas = PropostaContrato::where('id_usuario_avaliador', $user->id)
//             ->where('data', '<', now())
//             ->where('status', 'Finalizada')
//             ->get();

//         $pendentes = $propostas->filter(function ($proposta) {
//             return !FeedbackArtista::where('id_usuario_avaliador', $proposta->id_usuario_avaliador)
//                 ->where('id_artista', $proposta->id_artista)
//                 ->exists();
//         });

//         return response()->json($pendentes->values());
//     }

    public function store(Request $request)
    {
        $request->validate([
            'id_proposta' => 'required|exists:proposta_contrato,id',
            'nota' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:1000',
        ]);

        $proposta = PropostaContrato::find($request->id_proposta);

        FeedbackArtista::create([
            'id_artista' => $proposta->id_artista,
            'id_usuario_avaliador' => Auth::id(),
            'nota' => $request->nota,
            'comentario' => $request->comentario,
        ]);

        return redirect()->back()->with('success', 'Feedback enviado com sucesso.');
    }

    //
}
