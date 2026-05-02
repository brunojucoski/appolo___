<?php

namespace App\Http\Controllers;

use App\Models\PerguntaPropostaContrato;
use App\Models\RespostaPropostaPergunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerguntaPropostaContratoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if ((int) $user->tipo_usuario !== 2) {
            abort(403);
        }
        $portfolio = $user->portfolioArtista;
        if (! $portfolio) {
            return redirect()->route('perfil')->with('error', 'Crie seu portfólio antes de configurar o formulário de orçamento.');
        }

        $perguntas = $portfolio->perguntasPropostaContrato()->get();

        return view('propostas.perguntas_proposta', compact('portfolio', 'perguntas'));
    }

    public function store(Request $request)
    {
        $portfolio = $this->portfolioAutorizado();
        $rules = [
            'tipo' => 'required|in:texto,opcoes,anexo',
            'titulo' => 'required|string|max:500',
            'ordem' => 'nullable|integer|min:0',
        ];
        if ($request->input('tipo') === 'opcoes') {
            $rules['opcoes'] = 'required|array|min:2|max:20';
            $rules['opcoes.*'] = 'required|string|max:255';
        }
        $validated = $request->validate($rules);

        $opcoes = null;
        if (($validated['tipo'] ?? '') === 'opcoes') {
            $opcoes = array_values(array_filter(array_map('trim', $request->input('opcoes', []))));
            if (count($opcoes) < 2) {
                return back()->withErrors(['opcoes' => 'Informe pelo menos duas opções de resposta.'])->withInput();
            }
        }

        PerguntaPropostaContrato::create([
            'id_portfolio_artista' => $portfolio->id,
            'tipo' => $validated['tipo'],
            'titulo' => $validated['titulo'],
            'opcoes_json' => $opcoes,
            'ordem' => $validated['ordem'] ?? 0,
        ]);

        return back()->with('success', 'Pergunta adicionada.');
    }

    public function update(Request $request, PerguntaPropostaContrato $perguntaPropostaContrato)
    {
        $portfolio = $this->portfolioAutorizado();
        if ((int) $perguntaPropostaContrato->id_portfolio_artista !== (int) $portfolio->id) {
            abort(403);
        }

        $rules = [
            'titulo' => 'required|string|max:500',
            'ordem' => 'nullable|integer|min:0',
        ];
        if ($perguntaPropostaContrato->tipo === 'opcoes') {
            $rules['opcoes'] = 'required|array|min:2|max:20';
            $rules['opcoes.*'] = 'required|string|max:255';
        }
        $validated = $request->validate($rules);

        $data = [
            'titulo' => $validated['titulo'],
            'ordem' => $validated['ordem'] ?? $perguntaPropostaContrato->ordem,
        ];
        if ($perguntaPropostaContrato->tipo === 'opcoes') {
            $opcoes = array_values(array_filter(array_map('trim', $request->input('opcoes', []))));
            if (count($opcoes) < 2) {
                return back()->withErrors(['opcoes' => 'Informe pelo menos duas opções.'])->withInput();
            }
            $data['opcoes_json'] = $opcoes;
        }

        $perguntaPropostaContrato->update($data);

        return back()->with('success', 'Pergunta atualizada.');
    }

    public function destroy(PerguntaPropostaContrato $perguntaPropostaContrato)
    {
        $portfolio = $this->portfolioAutorizado();
        if ((int) $perguntaPropostaContrato->id_portfolio_artista !== (int) $portfolio->id) {
            abort(403);
        }
        if (RespostaPropostaPergunta::where('id_pergunta', $perguntaPropostaContrato->id)->exists()) {
            return back()->with('error', 'Não é possível excluir: já existem propostas com respostas a esta pergunta.');
        }
        $perguntaPropostaContrato->delete();

        return back()->with('success', 'Pergunta removida.');
    }

    private function portfolioAutorizado()
    {
        $user = Auth::user();
        if ((int) $user->tipo_usuario !== 2) {
            abort(403);
        }
        $portfolio = $user->portfolioArtista;
        if (! $portfolio) {
            abort(403, 'Portfólio não encontrado.');
        }

        return $portfolio;
    }
}
