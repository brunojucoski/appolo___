@extends('Components.navbarbootstrap')
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de propostas</title>
    <link href="{{ asset('css/perfil.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
</head>
<body>
@include('Components.navbarbootstrap')
<main class="container my-5" style="padding-top: 100px;">
    <h2 class="mb-4 botao_home text-uppercase">Formulário de Orçamento  </h2>
    <p class="text-muted">Defina quais perguntas o solicitante responderá ao clicar em <strong>Contratar</strong> no seu perfil público.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-header"><strong>Nova pergunta</strong></div>
        <div class="card-body">
            <form action="{{ route('perguntas-proposta.store') }}" method="POST" id="formNovaPergunta">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tipo</label>
                        <select name="tipo" class="form-select" id="tipoNovaPergunta" required>
                            <option value="texto">Texto livre</option>
                            <option value="opcoes">Opções (escolha única)</option>
                            <option value="anexo">Anexo (arquivo)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Título da pergunta</label>
                        <input type="text" name="titulo" class="form-control" required maxlength="500" placeholder="Ex.: Qual o tipo de evento?">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Ordem</label>
                        <input type="number" name="ordem" class="form-control" value="0" min="0">
                    </div>
                    <div class="col-md-12 d-none" id="blocoOpcoesNova">
                        <label class="form-label">Opções de resposta (mínimo 2)</label>
                        <div id="opcoesContainerNova">
                            <input type="text" name="opcoes[]" class="form-control mb-2" placeholder="Opção 1">
                            <input type="text" name="opcoes[]" class="form-control mb-2" placeholder="Opção 2">
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnAddOpcaoNova">+ opção</button>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-outline-custom">Adicionar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <h5 class="mb-3">Perguntas cadastradas</h5>
    @forelse($perguntas as $p)
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <span class="badge bg-secondary text-uppercase">{{ $p->tipo }}</span>
                        <span class="text-muted small">ordem {{ $p->ordem }}</span>
                        <p class="mb-1 mt-2 fw-semibold">{{ $p->titulo }}</p>
                        @if($p->tipo === 'opcoes' && count($p->opcoesList()))
                            <ul class="small mb-0">@foreach($p->opcoesList() as $i => $op)<li>{{ $op }}</li>@endforeach</ul>
                        @endif
                    </div>
                    <form action="{{ route('perguntas-proposta.destroy', $p) }}" method="POST" onsubmit="return confirm('Excluir esta pergunta?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                    </form>
                </div>
                <hr>
                <form action="{{ route('perguntas-proposta.update', $p) }}" method="POST" class="row g-2 align-items-end">
                    @csrf
                    @method('PUT')
                    <div class="col-md-8">
                        <label class="form-label small">Título</label>
                        <input type="text" name="titulo" class="form-control form-control-sm" value="{{ $p->titulo }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Ordem</label>
                        <input type="number" name="ordem" class="form-control form-control-sm" value="{{ $p->ordem }}" min="0">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-outline-custom w-100">Salvar</button>
                    </div>
                    @if($p->tipo === 'opcoes')
                        <div class="col-12">
                            <label class="form-label small">Opções</label>
                            @foreach($p->opcoesList() as $op)
                                <input type="text" name="opcoes[]" class="form-control form-control-sm mb-1" value="{{ $op }}">
                            @endforeach
                            <input type="text" name="opcoes[]" class="form-control form-control-sm mb-1" placeholder="Nova opção (opcional)">
                            <input type="text" name="opcoes[]" class="form-control form-control-sm" placeholder="Nova opção (opcional)">
                        </div>
                    @endif
                </form>
            </div>
        </div>
    @empty
        <p class="text-muted">Nenhuma pergunta ainda. Adicione ao menos uma para o botão <strong>Contratar</strong> aparecer no seu perfil.</p>
    @endforelse

    <a href="{{ route('usuarios.perfilPublico', Auth::id()) }}" class="btn btn-outline-secondary mt-3">Voltar ao perfil</a>
</main>
@include('Components.footer')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const tipoNova = document.getElementById('tipoNovaPergunta');
    const blocoOpcoes = document.getElementById('blocoOpcoesNova');
    const formNova = document.getElementById('formNovaPergunta');
    function syncOpcoesNova() {
        const show = tipoNova.value === 'opcoes';
        blocoOpcoes.classList.toggle('d-none', !show);
        document.querySelectorAll('#blocoOpcoesNova input[name="opcoes[]"]').forEach((inp) => {
            inp.disabled = !show;
        });
    }
    tipoNova.addEventListener('change', syncOpcoesNova);
    syncOpcoesNova();
    formNova.addEventListener('submit', function () {
        syncOpcoesNova();
    });
    document.getElementById('btnAddOpcaoNova')?.addEventListener('click', function () {
        const inp = document.createElement('input');
        inp.type = 'text';
        inp.name = 'opcoes[]';
        inp.className = 'form-control mb-2';
        inp.placeholder = 'Outra opção';
        document.getElementById('opcoesContainerNova').appendChild(inp);
    });
</script>
</body>
</html>
