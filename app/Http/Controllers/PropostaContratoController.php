<?php

namespace App\Http\Controllers;

use App\Models\PropostaContrato;
use App\Models\PortfolioArtista;
use App\Models\Notificacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PropostaContratoController extends Controller
{
    public function index()
    {
        $propostas = PropostaContrato::with(['artista', 'usuarioAvaliador'])->get();
        return response()->json($propostas);
    }


    
    public function store(Request $request)
    {
        $request->validate([
            'id_artista' => 'required|exists:portfolio_artistas,id',
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string|min:20',
            'data' => 'required|date|after:today',
        ], [
            'id_artista.required' => 'Artista não selecionado.',
            'id_artista.exists' => 'O artista informado não existe.',
            'titulo.required' => 'O título é obrigatório.',
            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.min' => 'A descrição deve conter pelo menos 20 caracteres.',
            'data.required' => 'A data é obrigatória.',
            'data.after' => 'A data deve ser posterior à data atual.',
        ]);

        $proposta = PropostaContrato::create([
            'id_artista' => $request->id_artista,
            'id_usuario_avaliador' => Auth::id(),
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'data' => $request->data,
            'status' => 'Aguardando resposta',
        ]);

        // Obtém o portfolio do artista 
        $portfolio = PortfolioArtista::with('usuario')->find($request->id_artista);
        
        if ($portfolio && $portfolio->usuario) {
            // Cria notificação para o artista
            Notificacao::create([
                'usuario_id' => $portfolio->usuario->id,
                'remetente_id' => Auth::id(),
                'mensagem' => "Você recebeu uma nova proposta: {$request->titulo}",
                'lida' => false,
                'proposta_id' => $proposta->id,
            ]);
        }

        return redirect()->back()->with('success', 'Proposta enviada com sucesso!');
    }

    public function show($id)
    {
        $proposta = PropostaContrato::with(['artista', 'usuarioAvaliador'])->findOrFail($id);
        return response()->json($proposta);
    }

    public function update(Request $request, $id)
    {
        $proposta = PropostaContrato::findOrFail($id);

        $validated = $request->validate([
            'titulo' => 'sometimes|string|max:255',
            'descricao' => 'sometimes|string',
            'data' => 'sometimes|date',
        ]);

        $proposta->update($validated);

        return response()->json($proposta);
    }

    public function destroy($id)
    {
        $proposta = PropostaContrato::findOrFail($id);
        $proposta->delete();

        return response()->json(['message' => 'Proposta excluída com sucesso.']);
    }

    public function notificacoes()
    {
        $usuario = Auth::user();

        if ($usuario->tipo_usuario != 2) {
            return response()->json([]);
        }

        $portfolio = $usuario->portfolioArtista;

        if (!$portfolio) {
            return response()->json([]);
        }

        $propostas = PropostaContrato::where('id_artista', $portfolio->id)
            ->with('usuarioAvaliador')
            ->latest()
            ->get();

        return response()->json($propostas);
    }

    public function responder(Request $request, $id)
    {
        try {
            $proposta = PropostaContrato::findOrFail($id);
            $usuario = Auth::user();

            // Verifica se o usuário é um artista (tipo_usuario = 2)
            if ($usuario->tipo_usuario != 2) {
                return redirect()->back()->with('error', 'Apenas artistas podem responder propostas.');
            }

            // Obtém o portfolio do artista
            $portfolio = $usuario->portfolioArtista;
            if (!$portfolio) {
                return redirect()->back()->with('error', 'Você precisa ter um portfólio para responder propostas.');
            }

            // Verifica se a proposta pertence ao artista
            if ($proposta->id_artista !== $portfolio->id) {
                return redirect()->back()->with('error', 'Esta proposta não pertence a você.');
            }

            $resposta = $request->input('status');
            $motivo = $request->input('motivo');

            if (!in_array($resposta, ['aceita', 'recusada'])) {
                return redirect()->back()->with('error', 'Resposta inválida.');
            }

            if (empty(trim($motivo))) {
                return redirect()->back()->with('error', 'O motivo não pode ficar em branco.');
            }

            // Atualiza o status da proposta
            if ($resposta === 'recusada') {
                $proposta->status = 'Recusada';
            } else {
                $hoje = now();
                $data_execucao = Carbon::parse($proposta->data);
                $proposta->status = ($data_execucao > $hoje) ? 'Aguardando execução' : 'Finalizada';
            }

            $proposta->motivo = $motivo;
            $proposta->save();

            // Prepara a mensagem da notificação
            $telefone = $usuario->telefone ? "Telefone para contato: {$usuario->telefone}. " : "";
            $mensagem = $resposta === 'recusada'
                ? "Sua proposta '{$proposta->titulo}' foi recusada pelo artista {$usuario->nome}. Motivo: \"{$motivo}\"."
                : "Sua proposta '{$proposta->titulo}' foi aprovada por {$usuario->nome} e será executada em " . $data_execucao->format('d/m/Y H:i') . ". {$telefone}Observações: \"{$motivo}\".";

            // Cria a notificação para o contratante
            Notificacao::create([
                'usuario_id' => $proposta->id_usuario_avaliador,
                'remetente_id' => $usuario->id,
                'mensagem' => $mensagem,
                'proposta_id' => $proposta->id,
                'lida' => false,
            ]);

            return redirect()->back()->with('success', 'Resposta registrada com sucesso.');

        } catch (\Exception $e) {
            \Log::error('Erro ao processar resposta da proposta: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->back()->with('error', 'Erro ao processar sua resposta.');
        }
    }

    

    public function lerTodas()
    {
        Notificacao::where('user_id', auth()->id())->update(['lida' => true]);
        return response()->json(['success' => true]);
    }

    public function minhasPropostas(Request $request)
{
    $usuario = Auth::user();
    $status = $request->input('status');
    $avaliadorId = $request->input('avaliador_id');

    $propostas = collect();

    if ($usuario->tipo_usuario == 2) { // Artista
        $portfolio = $usuario->portfolioArtista;
        if ($portfolio) {
            $query = PropostaContrato::where('id_artista', $portfolio->id)
                ->with(['usuarioAvaliador', 'artista.usuario']);

            if ($status) {
                $query->where('status', $status);
            }

            if ($avaliadorId) {
                $query->where('id_usuario_avaliador', $avaliadorId);
            }

            $propostas = $query->latest()->get();
        }
    } elseif ($usuario->tipo_usuario == 3) { // Contratante
        $query = PropostaContrato::where('id_usuario_avaliador', $usuario->id)
            ->with(['artista.usuario', 'usuarioAvaliador']);

        if ($status) {
            $query->where('status', $status);
        }

        $propostas = $query->latest()->get();
    }

    // Lista de avaliadores distintos (para filtro)
    $avaliadores = PropostaContrato::select('id_usuario_avaliador')
        ->with('usuarioAvaliador')
        ->distinct()
        ->get()
        ->pluck('usuarioAvaliador')
        ->unique('id');

    return view('propostas.minhas_propostas', compact('propostas', 'usuario', 'avaliadores'));
}

}