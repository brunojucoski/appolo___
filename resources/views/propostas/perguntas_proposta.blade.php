<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de propostas</title>
    <link href="{{ asset('css/perfil.css') }}" rel="stylesheet">
    <link href="{{ asset('css/perguntas-proposta.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
@include('Components.navbarbootstrap')

<main class="container perguntas-proposta-page my-5">
    <div class="perguntas-proposta-header">
        <div>
            <h2 class="botao_home text-uppercase mb-2">Formulário de Orçamento</h2>
            <p class="text-muted mb-0">Defina quais perguntas o solicitante responderá ao clicar em <strong>Contratar</strong> no seu perfil público.</p>
        </div>
        <button type="button" class="btn btn-outline-custom perguntas-icon-btn" data-bs-toggle="modal" data-bs-target="#novaPerguntaPropostaModal" aria-label="Adicionar pergunta">
            <i class="bi bi-plus-lg"></i>
        </button>
    </div>

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

    <section class="perguntas-list-card">
        <div class="perguntas-list-toolbar">
            <div>
                <h5 class="mb-1">Perguntas cadastradas</h5>
                <span class="text-muted small">
                    {{ $perguntas->count() }} pergunta{{ $perguntas->count() === 1 ? '' : 's' }} no formulário
                </span>
            </div>
           
        </div>

        <div class="perguntas-list">
            <div class="perguntas-list-head">
                <span>Ordem</span>
                <span>Tipo</span>
                <span>Título</span>
                <span>Ações</span>
            </div>

            @forelse($perguntas as $p)
                @php
                    $tipoLabels = [
                        'texto' => 'Texto livre',
                        'opcoes' => 'Opções',
                        'anexo' => 'Anexo',
                    ];
                    $opcoes = $p->opcoesList();
                @endphp
                <div class="perguntas-list-row">
                    <span class="perguntas-list-order">{{ $p->ordem }}</span>
                    <span class="perguntas-list-type">{{ $tipoLabels[$p->tipo] ?? $p->tipo }}</span>
                    <span class="perguntas-list-title" title="{{ $p->titulo }}">
                        {{ $p->titulo }}
                        @if($p->tipo === 'opcoes' && count($opcoes))
                            <small>{{ implode(' | ', $opcoes) }}</small>
                        @endif
                    </span>
                    <span class="perguntas-list-actions">
                        <button
                            type="button"
                            class="btn btn-outline-custom btn-sm perguntas-icon-btn"
                            data-update-url="{{ route('perguntas-proposta.update', $p) }}"
                            data-pergunta-titulo="{{ $p->titulo }}"
                            data-pergunta-ordem="{{ $p->ordem }}"
                            data-pergunta-tipo="{{ $p->tipo }}"
                            data-pergunta-opcoes="{{ base64_encode(json_encode($opcoes)) }}"
                            onclick="openEditPerguntaPropostaModal(this)"
                            aria-label="Editar pergunta {{ $p->titulo }}"
                        >
                            <i class="bi bi-journal-text"></i>
                        </button>
                        <form action="{{ route('perguntas-proposta.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Excluir esta pergunta?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm perguntas-icon-btn" aria-label="Excluir pergunta {{ $p->titulo }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </span>
                </div>
            @empty
                <div class="perguntas-empty">
                    Nenhuma pergunta cadastrada ainda. Use o botão de adicionar para montar seu formulário de orçamento.
                </div>
            @endforelse
        </div>
    </section>

    <a href="{{ route('usuarios.perfilPublico', Auth::id()) }}" class="btn btn-outline-secondary mt-3">Voltar ao perfil</a>
</main>

<div class="modal fade" id="novaPerguntaPropostaModal" tabindex="-1" aria-labelledby="novaPerguntaPropostaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('perguntas-proposta.store') }}" method="POST" id="formNovaPergunta">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="novaPerguntaPropostaModalLabel">Nova pergunta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tipoNovaPergunta" class="form-label">Tipo</label>
                        <select name="tipo" class="form-select" id="tipoNovaPergunta" required>
                            <option value="texto">Texto livre</option>
                            <option value="opcoes">Opções (escolha única)</option>
                            <option value="anexo">Anexo (arquivo)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tituloNovaPergunta" class="form-label">Título da pergunta</label>
                        <input type="text" name="titulo" id="tituloNovaPergunta" class="form-control" required maxlength="500" placeholder="Ex.: Qual o tipo de evento?">
                    </div>
                    <div class="mb-3">
                        <label for="ordemNovaPergunta" class="form-label">Ordem</label>
                        <input type="number" name="ordem" id="ordemNovaPergunta" class="form-control" value="0" min="0">
                    </div>
                    <div class="mb-0 d-none" id="blocoOpcoesNova">
                        <label class="form-label">Opções de resposta</label>
                        <div id="opcoesContainerNova">
                            <input type="text" name="opcoes[]" class="form-control mb-2" placeholder="Opção 1">
                            <input type="text" name="opcoes[]" class="form-control mb-2" placeholder="Opção 2">
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnAddOpcaoNova">
                            <i class="bi bi-plus-lg"></i> Opção
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-outline-custom">Adicionar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editarPerguntaPropostaModal" tabindex="-1" aria-labelledby="editarPerguntaPropostaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editarPerguntaPropostaForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editarPerguntaPropostaModalLabel">Editar pergunta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editar_pergunta_tipo" class="form-label">Tipo</label>
                        <input type="text" id="editar_pergunta_tipo" class="form-control" readonly>
                        <div class="form-text">O tipo da pergunta é definido na criação.</div>
                    </div>
                    <div class="mb-3">
                        <label for="editar_pergunta_titulo" class="form-label">Título da pergunta</label>
                        <input type="text" name="titulo" id="editar_pergunta_titulo" class="form-control" required maxlength="500">
                    </div>
                    <div class="mb-3">
                        <label for="editar_pergunta_ordem" class="form-label">Ordem</label>
                        <input type="number" name="ordem" id="editar_pergunta_ordem" class="form-control" min="0">
                    </div>
                    <div class="mb-0 d-none" id="blocoOpcoesEditar">
                        <label class="form-label">Opções de resposta</label>
                        <div id="opcoesContainerEditar"></div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnAddOpcaoEditar">
                            <i class="bi bi-plus-lg"></i> Opção
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-outline-custom">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('Components.footer')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const tipoLabels = {
        texto: 'Texto livre',
        opcoes: 'Opções (escolha única)',
        anexo: 'Anexo (arquivo)'
    };

    function createOptionInput(value = '', placeholder = 'Outra opção', className = 'form-control mb-2') {
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'opcoes[]';
        input.className = className;
        input.placeholder = placeholder;
        input.value = value;
        return input;
    }

    function setOptionsEnabled(container, enabled) {
        container.querySelectorAll('input[name="opcoes[]"]').forEach((input) => {
            input.disabled = !enabled;
        });
    }

    function syncOpcoesNova() {
        const tipoNova = document.getElementById('tipoNovaPergunta');
        const blocoOpcoes = document.getElementById('blocoOpcoesNova');
        if (!tipoNova || !blocoOpcoes) return;

        const show = tipoNova.value === 'opcoes';
        blocoOpcoes.classList.toggle('d-none', !show);
        setOptionsEnabled(blocoOpcoes, show);
    }

    function openEditPerguntaPropostaModal(button) {
        const form = document.getElementById('editarPerguntaPropostaForm');
        const tipo = document.getElementById('editar_pergunta_tipo');
        const titulo = document.getElementById('editar_pergunta_titulo');
        const ordem = document.getElementById('editar_pergunta_ordem');
        const blocoOpcoes = document.getElementById('blocoOpcoesEditar');
        const opcoesContainer = document.getElementById('opcoesContainerEditar');
        if (!form || !tipo || !titulo || !ordem || !blocoOpcoes || !opcoesContainer) return;

        const tipoValue = button.dataset.perguntaTipo || '';
        form.action = button.dataset.updateUrl || '';
        tipo.value = tipoLabels[tipoValue] || tipoValue;
        titulo.value = button.dataset.perguntaTitulo || '';
        ordem.value = button.dataset.perguntaOrdem || 0;

        opcoesContainer.innerHTML = '';
        const showOptions = tipoValue === 'opcoes';
        blocoOpcoes.classList.toggle('d-none', !showOptions);

        if (showOptions) {
            let opcoes = [];
            try {
                opcoes = JSON.parse(atob(button.dataset.perguntaOpcoes || 'W10='));
            } catch (error) {
                opcoes = [];
            }

            const normalizedOptions = opcoes.length ? opcoes : ['', ''];
            normalizedOptions.forEach((opcao, index) => {
                opcoesContainer.appendChild(createOptionInput(opcao, `Opção ${index + 1}`, 'form-control mb-2'));
            });
            setOptionsEnabled(blocoOpcoes, true);
        }

        bootstrap.Modal.getOrCreateInstance(document.getElementById('editarPerguntaPropostaModal')).show();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const tipoNova = document.getElementById('tipoNovaPergunta');
        const formNova = document.getElementById('formNovaPergunta');

        tipoNova?.addEventListener('change', syncOpcoesNova);
        formNova?.addEventListener('submit', syncOpcoesNova);
        syncOpcoesNova();

        document.getElementById('btnAddOpcaoNova')?.addEventListener('click', function () {
            document.getElementById('opcoesContainerNova')?.appendChild(createOptionInput());
        });

        document.getElementById('btnAddOpcaoEditar')?.addEventListener('click', function () {
            document.getElementById('opcoesContainerEditar')?.appendChild(createOptionInput());
        });
    });
</script>
</body>
</html>
