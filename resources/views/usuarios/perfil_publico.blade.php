<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Perfil Público</title>
    <link href="{{ asset('css/perfil.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
@include('Components.navbarbootstrap')

<main>

    {{-- Funções de formatação (mantidas) --}}
    @php
        function formatarCep($cep) {
            return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cep);
        }
        function formatarTelefone($tel) {
            $tel = preg_replace('/\D/', '', $tel);
            return (strlen($tel) === 11)
                ? preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $tel)
                : preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $tel);
        }
    @endphp

    @php
        $portfolio = $usuario->portfolioArtista;
    @endphp

  
    {{-- Modais de Erro/Sucesso --}}
@if(session('success'))
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="text-nome" id="successModalLabel"> APPOLO </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ session('success') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal"> Fechar </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ----- CÓDIGO ADICIONAL PARA FORÇAR A LIMPEZA DO BOOTSTRAP -----
            // 1. Garante que a classe 'modal-open' seja removida do body
            document.body.classList.remove('modal-open');

            // 2. Remove qualquer backdrop de modal que possa ter ficado preso
            const existingBackdrops = document.querySelectorAll('.modal-backdrop');
            existingBackdrops.forEach(backdrop => backdrop.remove());

            // 3. Opcional, mas seguro: Força o 'hide' em qualquer modal 'show'
            document.querySelectorAll('.modal.show').forEach(openModal => {
                const modalInstance = bootstrap.Modal.getInstance(openModal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            });
            // ----- FIM DO CÓDIGO DE LIMPEZA -----

            var successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
        });
    </script>
@endif


    {{-- 1. SEÇÃO PRINCIPAL DE INFORMAÇÕES DO PERFIL (TOPO DA PÁGINA) --}}
    <div class="p-3">
        <section class="py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center text-md-start mb-4 mb-md-0 imagem_perfil">
                        <img src="{{ $usuario->foto_perfil && file_exists(public_path('storage/' . $usuario->foto_perfil)) ? asset('storage/' . $usuario->foto_perfil) : asset('imgs/user.png') }}" class="rounded-circle border border-4 border-tertiary shadow profile-img" alt="Perfil">
                    </div>
                    <div class="col-md-9">
                        <h1 class="text-nome">{{ $usuario->nome }} </h1>

                        @if($usuario->tipo_usuario == 2)
                            <h3 class="text-nome"> {{ $portfolio->nome_artistico ?? '' }} </h3>
                        @endif

                        <p class="text-muted"><i class="bi bi-calendar"></i> {{ $usuario->idade }} anos </p>
                        <p class="text-muted"><i class="bi bi-geo-alt"></i> {{ $usuario->cidade ?? 'Localidade não definida' }} </p>
                        <p class="text-muted"><i class="bi bi-telephone"></i> {{ formatarTelefone($usuario->telefone) }}</p>
                        <p><strong>Endereço:</strong> {{ formatarCep($usuario->cep) }} , {{ $usuario->bairro }} , {{ $usuario->endereco }}</p>

                        @if($usuario->tipo_usuario == 2)
                            <p><i class="bi bi-brush"></i> {{ $portfolio->descricao ?? 'Descrição do portfólio não disponível' }}</p>
                            <div class="social-icons my-3">
                                <a href="{{$portfolio->link_instagram ?? '' }}" class="text-primary fs-4 me-3 text-decoration-none" target="_blank">
                                    <i class="bi bi-instagram"></i>
                                </a>
                                <a href="{{$portfolio->link_behance ?? ''}}" class="text-primary fs-4 me-3" target="_blank">
                                    <i class="bi bi-link-45deg"></i>
                                </a>
                                {{-- DIV DA MÉDIA DE AVALIAÇÕES --}}
                                <div class="mt-1">
                                    @php
                                        $feedbacks = $feedbacksParaMedia;
                                        $media = $feedbacks->avg('nota');
                                    @endphp

                                    @if($media)
                                        <strong>{{ number_format($media, 1) }}</strong> ⭐ ({{ $feedbacks->count() }} avaliação{{ $feedbacks->count() > 1 ? 's' : '' }})
                                    @else
                                        <em>Sem avaliações ainda</em>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @auth
                            @if(Auth::user()->tipo_usuario == 3 && $usuario->tipo_usuario == 2)
                                @if($usuario->portfolioArtista)
                                    <button class="btn btn-sm btn-outline-custom" data-bs-toggle="modal" data-bs-target="#modalPropostaContrato">
                                        Contratar
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-outline-custom" disabled>
                                        Artista com cadastro incompleto
                                    </button>
                                @endif
                            @endif
                        @else
                            <a href="{{ url('/cadastro/contratante') }}" class="btn btn-sm btn-outline-custom">
                                Cadastre-se para contratar
                            </a>
                        @endauth

                        @auth
                            @if(auth()->user()->id === $usuario->id && auth()->user()->tipo_usuario == 2)
                                <button class="btn btn-outline-custom" data-bs-toggle="modal" data-bs-target="#editModalportfolio">
                                    <i class="bi bi-pencil"></i> {{ $portfolio ? 'Editar Portfólio' : 'Criar Portfólio' }}
                                </button>
                            @endif
                        @endauth

                        {{-- Categorias (mantidas) --}}
                        @if($usuario->tipo_usuario == 2)
                            @if($usuario->categoriasArtisticas && $usuario->categoriasArtisticas->count() > 0)
                                <div class="mb-3 p-3">
                                    <label class="form-label">Categorias : </label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($usuario->categoriasArtisticas as $cat)
                                            <label class="btn btn-sm btn-outline-custom">{{ $cat->nome }}</label>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <p class="text-muted">Nenhuma categoria selecionada</p>
                            @endif
                        @endif
                    </div> {{-- Fim col-md-9 --}}
                </div> {{-- Fim row --}}
            </div> {{-- Fim container --}}
        </section> {{-- Fim section.py-5 --}}
    </div> {{-- Fim p-3 --}}


    {{-- 2. SEÇÃO DE PORTFÓLIO (CONDICIONAL: APENAS PARA ARTISTAS) --}}
    @if($usuario->tipo_usuario == 2)
        @if(isset($posts) && $posts->count() > 0)
            <div class="p-3 align-itens-center text-center">
                <h3 class="text-nome"> Portfólio </h3>
            </div>
            <section class="py-4 bg-light">
                <div class="container">
                    <div class="row g-4">
                        @foreach($posts as $post)
                            <div class="col-md-4">
                                <div class="card shadow-sm">
                                    <div id="carouselPost{{ $post->id }}" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            @foreach($post->imagens as $index => $img)
                                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                    <img src="{{ asset('storage/' . $img->caminho_imagem) }}" alt="Imagem do post" class="d-block w-100 rounded gallery-img"
                                                         data-bs-toggle="modal" data-bs-target="#modalPost{{ $post->id }}">
                                                </div>
                                            @endforeach
                                        </div>
                                        @if(count($post->imagens) > 1)
                                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselPost{{ $post->id }}" data-bs-slide="prev">
                                                <span class="carousel-control-prev-icon"></span>
                                            </button>
                                            <button class="carousel-control-next" type="button" data-bs-target="#carouselPost{{ $post->id }}" data-bs-slide="next">
                                                <span class="carousel-control-next-icon"></span>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- MODAL INDIVIDUAL PARA CADA POST (DEFINIDA AQUI DENTRO DO FOREACH) --}}
                           <div class="modal fade" id="modalPost{{ $post->id }}" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-xl modal-dialog-centered">
                                  <div class="modal-content">
                                      <div class="modal-header border-0">
                                          <div class="d-flex w-100 justify-content-end gap-2">
                                              @auth
                                                  @if(Auth::user()->tipo_usuario == 2)
                                                      {{-- BOTÃO EDITAR: AGORA ABRE A MODAL DE EDIÇÃO --}}
                                                      <button type="button" class="btn btn-outline-custom btn-sm"
                                                              data-bs-toggle="modal"
                                                              data-bs-target="#editPostModal"
                                                              data-post-id="{{ $post->id }}"
                                                              data-post-nome="{{ $post->nome }}"
                                                              data-post-descricao="{{ $post->descricao }}"
                                                              data-post-imagens="{{ $post->imagens->map(fn($img) => ['id' => $img->id, 'caminho' => asset('storage/' . $img->caminho_imagem)])->toJson() }}" 
                                                              onclick="openEditPostModal(this)">
                                                          <i class="bi bi-pencil"></i> Editar
                                                      </button>

                                                      {{-- BOTÃO APAGAR: AGORA ABRE A MODAL DE CONFIRMAÇÃO DE EXCLUSÃO --}}
                                                      <button type="button" class="btn btn-outline-custom btn-sm"
                                                              data-bs-toggle="modal"
                                                              data-bs-target="#deletePostConfirmModal" {{-- ID da nova modal de confirmação --}}
                                                              data-post-id="{{ $post->id }}"
                                                              onclick="openDeletePostConfirmModal(this)"> {{-- Chama a função JS --}}
                                                          <i class="bi bi-trash"></i> Apagar
                                                      </button>
                                                  @endif
                                              @endauth
                                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                          </div>
                                      </div>
                                        <div class="modal-body">
                                            <div class="row g-4">
                                                <div class="col-lg-8">
                                                    <div id="carouselModal{{ $post->id }}" class="carousel slide" data-bs-ride="carousel">
                                                        <div class="carousel-inner">
                                                            @foreach($post->imagens as $index => $img)
                                                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                                    <img src="{{ asset('storage/' . $img->caminho_imagem) }}" class="d-block w-100 rounded" alt="Imagem Modal">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        @if(count($post->imagens) > 1)
                                                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselModal{{ $post->id }}" data-bs-slide="prev">
                                                                <span class="carousel-control-prev-icon"></span>
                                                            </button>
                                                            <button class="carousel-control-next" type="button" data-bs-target="#carouselModal{{ $post->id }}" data-bs-slide="next">
                                                                <span class="carousel-control-next-icon"></span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <div class="d-flex align-items-center mb-4">
                                                        <img src="{{ $usuario->foto_perfil ? asset('storage/' . $usuario->foto_perfil) : asset('imgs/user.png') }}" class="rounded-circle me-3" width="60" height="60" alt="Avatar">
                                                        <div>
                                                            <h5 class="mb-0 text-primary">{{ $post->nome }}</h5>
                                                            <small class="text-muted">{{ $usuario->idade }} anos | {{ $usuario->cidade ?? 'Localidade não definida' }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="bg-light p-3 rounded">
                                                        <p>{{ $post->descricao }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div> {{-- Fim row g-4 --}}
                </div> {{-- Fim container --}}
            </section> {{-- Fim section.py-4 bg-light --}}
        @else
            {{-- MENSAGEM PARA ARTISTA SEM POSTS (apenas se for o próprio perfil do artista) --}}
            @auth
                @if(Auth::user()->id === $usuario->id && Auth::user()->tipo_usuario == 2)
                    <div class="container my-5">
                        <div class="col-12 text-center">
                            <div class="card shadow-sm p-4">
                                <h4 class="mb-3">Você ainda não tem posts</h4>
                                <p class="text-muted">Comece a compartilhar seu trabalho com o mundo!</p>
                                <button class="btn btn-outline-custom" data-bs-toggle="modal" data-bs-target="#postModal">
                                    <i class="bi bi-plus-circle"></i> Faça seu primeiro post
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth
        @endif
    @endif {{-- FIM DA SEÇÃO CONDICIONAL DE PORTFÓLIO --}}


    {{-- 3. SEÇÃO DE ÚLTIMAS AVALIAÇÕES (SEMPRE VISÍVEL PARA AMBOS OS TIPOS DE USUÁRIO) --}}
    <section class="container my-5">
        <div class="p-3 align-itens-center text-center">
            <h3 class="text-nome"> Últimas Avaliações </h3>
        </div>
        @if($feedbacksParaLista->isEmpty())
            <p class="text-center text-muted">Ainda não há avaliações para este perfil.</p>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($feedbacksParaLista as $feedback)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-2">
                                    @if($feedback->avaliador)
                                        Avaliado por: <span class="fw-bold">{{ $feedback->avaliador->nome }}</span>
                                    @else
                                        Avaliado por: <span class="text-muted">Usuário Removido</span>
                                    @endif
                                </h5>
                                <div class="mb-3">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $feedback->nota ? '-fill text-warning' : '' }} fs-5"></i>
                                    @endfor
                                </div>
                                <p class="card-text">{{ $feedback->comentario }}</p>
                            </div>
                            <div class="card-footer bg-white border-0">
                                <small class="text-muted">Avaliado em: {{ $feedback->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>


    {{-- 4. MODAIS GERAIS  --}}
    {{-- Modal Proposta de Contrato --}}
    @if($usuario->portfolioArtista)
        <div class="modal fade p-5 mx-auto modal_proposta" id="modalPropostaContrato" tabindex="-1" role="dialog" aria-labelledby="modalPropostaContratoLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" style="max-width: 60%; margin: auto;">
                <form action="{{ route('propostas.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_artista" value="{{ $usuario->portfolioArtista->id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalPropostaContratoLabel">Enviar Proposta</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" class="form-control" name="titulo" required>
                            </div>
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição da Proposta</label>
                                <textarea name="descricao" class="form-control" rows="4" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="data" class="form-label">Data desejada</label>
                                <input type="datetime-local" class="form-control" name="data" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-outline-custom">Enviar Proposta</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal de Edição de Post (ÚNICA, FORA DO LOOP DE POSTS) --}}
<div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="editPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editPostForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- Método HTTP para atualização --}}

                <div class="modal-header">
                    <h5 class="modal-title" id="editPostModalLabel">Editar Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="post_id" id="edit_post_id"> {{-- Campo oculto para o ID do post --}}

                    <div class="mb-3">
                        <label for="edit_nome" class="form-label">Título</label>
                        <input type="text" class="form-control" name="nome" id="edit_nome" required>
                    </div>

                    <div class="mb-4">
                        <label for="edit_descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" name="descricao" id="edit_descricao" rows="3" required></textarea>
                    </div>

                    {{-- Seção para exibir imagens existentes e permitir remoção --}}
                    <div class="mb-3" id="existing_images_preview">
                        <label class="form-label">Imagens Atuais:</label>
                        <div class="d-flex flex-wrap gap-2" id="existing_images_container">
                            {{-- Imagens serão carregadas aqui via JS --}}
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_imagens" class="form-label">Adicionar Novas Imagens</label>
                        <input class="form-control" type="file" name="imagens[]" id="edit_imagens" multiple>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary-custom">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal de Confirmação de Exclusão de Post (ÚNICA) --}}
<div class="modal fade" id="deletePostConfirmModal" tabindex="-1" aria-labelledby="deletePostConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePostConfirmModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>Tem certeza que deseja apagar este post?</p>
                <form id="deletePostForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="post_id_to_delete" id="post_id_to_delete">
                    <button type="submit" class="btn btn-danger me-2">Apagar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>

    {{-- Modal Editar/Criar Portfolio --}}
    <div class="modal fade" id="editModalportfolio" tabindex="-1" aria-labelledby="editModalportfolioLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ $portfolio ? route('portfolio.update', $portfolio->id) : route('portfolio.store') }}" method="POST">
                    @csrf
                    @if($portfolio)
                        @method('PUT')
                    @endif
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalportfolioLabel">
                            {{ $portfolio ? 'Editar Portfólio' : 'Criar Portfólio' }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nome_artistico" class="form-label">Nome Artístico</label>
                            <input type="text" name="nome_artistico" class="form-control" value="{{ $portfolio->nome_artistico ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea name="descricao" class="form-control">{{ $portfolio->descricao ?? '' }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="link_instagram" class="form-label">Link do Instagram</label>
                            <input type="text" name="link_instagram" class="form-control" value="{{ $portfolio->link_instagram ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="link_behance" class="form-label">Link Pessoal</label>
                            <input type="text" name="link_behance" class="form-control" value="{{ $portfolio->link_behance ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Categorias Artísticas</label>
                            <div class="d-flex flex-wrap gap-2" id="categorias-container" autocomplete="off">
                                @foreach ($categorias as $categoria)
                                    <div class="form-check">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="categorias[]"
                                            value="{{ $categoria->id }}"
                                            id="categoria_{{ $categoria->id }}"
                                            {{ in_array($categoria->id, $categoriasSelecionadas) ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label" for="categoria_{{ $categoria->id }}">
                                            {{ $categoria->nome }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-custom">{{ $portfolio ? 'Salvar Alterações' : 'Criar Portfólio' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal para criar novo Post --}}
    @auth
        @if(Auth::user()->tipo_usuario == 2)
            <div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title nav-link" id="postModalLabel">Novo Post</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="nome" class="form-label">Título</label>
                                    <input type="text" class="form-control" name="nome" id="nome" placeholder="Título do seu post">
                                </div>
                                <div class="mb-4">
                                    <label for="descricao" class="form-label">Descrição</label>
                                    <textarea class="form-control" name="descricao" id="descricao" rows="3" placeholder="Descreva sua obra"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="imagens" class="form-label">Imagens</label>
                                    <input class="form-control" type="file" name="imagens[]" id="imagens" multiple>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary-custom">Publicar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth

</main>

@include('Components.footer')


<script>
    // Função para abrir a modal de edição de post
    function openEditPostModal(button) {
        const postId = button.dataset.postId;
        const postNome = button.dataset.postNome;
        const postDescricao = button.dataset.postDescricao;

        // Preenche os campos do formulário na modal de edição
        document.getElementById('edit_post_id').value = postId;
        document.getElementById('edit_nome').value = postNome;
        document.getElementById('edit_descricao').value = postDescricao;

        // Define a action do formulário para a rota de update
        const editForm = document.getElementById('editPostForm');
        editForm.action = `/posts/${postId}`; 

       
        const existingImagesContainer = document.getElementById('existing_images_container');
        existingImagesContainer.innerHTML = '';


        const postImagensJson = button.dataset.postImagens;
        if (postImagensJson) {
            const postImagens = JSON.parse(postImagensJson);
            postImagens.forEach(img => {
                const imgDiv = document.createElement('div');
                imgDiv.className = 'd-flex align-items-center me-2 mb-2';
                imgDiv.innerHTML = `
                    <img src="${img.caminho}" class="img-thumbnail me-2" style="width: 80px; height: 80px; object-fit: cover;" alt="Imagem do Post">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="imagens_para_remover[]" value="${img.id}" id="remove_img_${img.id}">
                        <label class="form-check-label" for="remove_img_${img.id}">Remover</label>
                    </div>
                `;
                existingImagesContainer.appendChild(imgDiv);
            });
        }

        // Abre a modal de edição
        const editModal = new bootstrap.Modal(document.getElementById('editPostModal'));
        editModal.show();
    }

    // Função para abrir a modal de confirmação de exclusão
    function openDeletePostConfirmModal(button) {
        const postId = button.dataset.postId;
        document.getElementById('post_id_to_delete').value = postId; // Define o ID no input oculto
        document.getElementById('deletePostForm').action = `/posts/${postId}`; // Define a action do formulário

        const deleteConfirmModal = new bootstrap.Modal(document.getElementById('deletePostConfirmModal'));
        deleteConfirmModal.show();
    }
</script>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</body>
</html>