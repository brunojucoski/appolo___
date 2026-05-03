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
@if(session('success') && !session('prompt_whatsapp_proposta'))
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

@if(session('prompt_whatsapp_proposta') && session('whatsapp_proposta_url'))
    <div class="modal fade" id="modalWhatsappPosProposta" tabindex="-1" aria-labelledby="modalWhatsappPosPropostaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="text-nome" id="modalWhatsappPosPropostaLabel">Proposta enviada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <p class="text-success small mb-2">Proposta enviada com sucesso!</p>
                    <p class="mb-0">Deseja também informar ao profissional via WhatsApp sobre o seu orçamento enviado?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-custom" id="btnWhatsappPropostaSim">Sim</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Não</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var url = @json(session('whatsapp_proposta_url'));
            var el = document.getElementById('modalWhatsappPosProposta');
            if (!el || !url) return;
            var modal = new bootstrap.Modal(el);
            modal.show();
            document.getElementById('btnWhatsappPropostaSim').addEventListener('click', function () {
                window.open(url, '_blank');
                modal.hide();
            });
        });
    </script>
@endif

@if(session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif


    {{-- 1. SEÇÃO PRINCIPAL DE INFORMAÇÕES DO PERFIL (TOPO DA PÁGINA) --}}
    <div class="p-3">
        <section class="py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center text-md-start mb-4 mb-md-0 imagem_perfil">
                        <img src="{{ $usuario->foto_perfil && file_exists(public_path('storage/' . $usuario->foto_perfil)) ? asset('storage/' . $usuario->foto_perfil) : asset('imgs/user.png') }}" class="rounded-circle  profile-img" alt="Perfil">
                    </div>
                    <div class="col-md-9">
                        <h1 class="text-nome">{{ $usuario->nome }} </h1>

                        @if($usuario->tipo_usuario == 2)
                            <h3 class="text-nome"> {{ $portfolio->nome_artistico ?? '' }} </h3>
                        @endif

                        <div class="d-none d-lg-block perfil-dados-resumo">
                            <p class="text-muted mb-1"><i class="bi bi-calendar"></i> {{ $usuario->idade }} anos </p>
                            <p class="text-muted mb-1"><i class="bi bi-geo-alt"></i> {{ $usuario->cidade ?? 'Localidade não definida' }} </p>
                            <p class="text-muted mb-1"><i class="bi bi-telephone"></i> {{ formatarTelefone($usuario->telefone) }}</p>
                            <p class="mb-2"><strong>Endereço:</strong> {{ formatarCep($usuario->cep) }} , {{ $usuario->bairro }} , {{ $usuario->endereco }}</p>
                            @if($usuario->tipo_usuario == 2)
                                <p class="mb-2"><i class="bi bi-brush"></i> {{ $portfolio->descricao ?? 'Descrição do portfólio não disponível' }}</p>
                            @endif
                        </div>

                        <div class="d-lg-none perfil-mais-info-mobile mb-2">
                            <button class="btn btn-custom w-100 d-flex justify-content-between align-items-center perfil-mais-info-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#maisInformacoesPerfil" aria-expanded="false" aria-controls="maisInformacoesPerfil">
                                <span>Mais informações</span>
                                <i class="bi bi-chevron-down"></i>
                            </button>
                            <div class="collapse perfil-mais-info-collapse mt-2" id="maisInformacoesPerfil">
                                <div class="perfil-mais-info-inner border rounded-3 p-3">
                                    <p class="text-muted mb-2"><i class="bi bi-calendar"></i> {{ $usuario->idade }} anos </p>
                                    <p class="text-muted mb-2"><i class="bi bi-geo-alt"></i> {{ $usuario->cidade ?? 'Localidade não definida' }} </p>
                                    <p class="text-muted mb-2"><i class="bi bi-telephone"></i> {{ formatarTelefone($usuario->telefone) }}</p>
                                    <p class="mb-2"><strong>Endereço:</strong> {{ formatarCep($usuario->cep) }} , {{ $usuario->bairro }} , {{ $usuario->endereco }}</p>
                                    @if($usuario->tipo_usuario == 2)
                                        <p class="mb-0"><i class="bi bi-brush"></i> {{ $portfolio->descricao ?? 'Descrição do portfólio não disponível' }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($usuario->tipo_usuario == 2)
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

                        @guest
                            @if($usuario->tipo_usuario == 2 && $usuario->portfolioArtista && $usuario->portfolioArtista->perguntasPropostaContrato->count() > 0)
                                <button type="button" class="btn btn-sm btn-outline-custom" data-bs-toggle="modal" data-bs-target="#modalConviteCadastroSolicitante">
                                    Enviar orçamento
                                </button>
                               
                            @else
                             
                            @endif
                        @else
                            @if(Auth::user()->tipo_usuario == 3 && $usuario->tipo_usuario == 2)
                                @if($usuario->portfolioArtista && $usuario->portfolioArtista->perguntasPropostaContrato->count() > 0)
                                    <button type="button" class="btn btn-sm btn-outline-custom" data-bs-toggle="modal" data-bs-target="#modalPropostaContrato">
                                        Enviar orçamento
                                    </button>
                                @elseif($usuario->portfolioArtista)
                                    <button class="btn btn-sm btn-outline-custom" type="button" disabled title="Este artista ainda não configurou o formulário de proposta.">
                                        Orçamento indisponível
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-outline-custom" disabled>
                                        Artista com cadastro incompleto
                                    </button>
                                @endif
                            @endif
                        @endguest

                        @auth
                            @if(auth()->user()->id === $usuario->id && auth()->user()->tipo_usuario == 2)
                                <button class="btn btn-outline-custom" data-bs-toggle="modal" data-bs-target="#editModalportfolio">
                                    <i class="bi bi-pencil"></i> {{ $portfolio ? 'Editar Portfólio' : 'Criar Portfólio' }}
                                </button>
                                @if($portfolio)
                                    <a href="{{ route('perguntas-proposta.index') }}" class="btn btn-outline-custom ms-1">
                                        <i class="bi bi-ui-checks"></i> Formulário de orçamento
                                    </a>
                                @endif
                            @endif
                        @endauth

                        {{-- Categorias (mantidas) --}}
                        @if($usuario->tipo_usuario == 2)
                            @if($usuario->categoriasArtisticas && $usuario->categoriasArtisticas->count() > 0)
                                <div class="mb-3 p-3">
                                    <label class="form-label">Áreas de atuação : </label>
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
        @php
            $posts = $posts ?? collect();
            $categoriasPortfolio = $categoriasPortfolio ?? collect();
            $categoriaAtiva = $categoriaAtiva ?? null;
            $totalPostsPortfolio = $portfolio ? $portfolio->posts->count() : 0;
            $exibirBlocoPortfolio = $portfolio && $usuario->tipo_usuario == 2;
        @endphp
        @if($exibirBlocoPortfolio)
            <div class=" align-itens-center text-center">
                <div class="d-flex flex-wrap justify-content-center align-items-center gap-2">
                    <h3 class="text-nome mb-0"> Portfólio </h3>
                    @auth
                        @if(Auth::id() === $usuario->id && Auth::user()->tipo_usuario == 2 && $portfolio)
                            <button type="button" class="btn btn-sm btn-outline-custom" data-bs-toggle="modal" data-bs-target="#categoriasPortfolioModal">
                                <i class="bi bi-folder-plus"></i> Categorias do Portfólio
                            </button>
                         
                        @endif
                    @endauth
                </div>
            </div>

            @if($categoriaAtiva)
                <div class="container pb-2">
                    <a href="{{ route('usuarios.perfilPublico', $usuario->id) }}" class="text-simples">&larr; Voltar</a>
                    <div class="mt-3 text-center">
                        <h4 class="text-nome">{{ $categoriaAtiva->nome }}</h4>
                        @if($categoriaAtiva->descricao)
                            <p class="text-muted mx-auto" style="max-width: 640px;">{{ $categoriaAtiva->descricao }}</p>
                        @endif
                    </div>
                </div>
            @elseif($categoriasPortfolio->isNotEmpty())
                <section class="py-2">
                    <div class="container">
                        <h4 class="text-nome h5 text-center mb-4"></h4>
                        <div class="row g-4">
                            @foreach($categoriasPortfolio as $cat)
                                @php
                                    $cover = $cat->coverPost;
                                    $thumb = $cover && $cover->imagens->isNotEmpty()
                                        ? asset('storage/' . $cover->imagens->first()->caminho_imagem)
                                        : null;
                                @endphp
                                <div class="col-md-4">
                                    <a href="{{ route('usuarios.perfilPublico', ['id' => $usuario->id, 'categoria' => $cat->id]) }}" class="text-decoration-none text-dark">
                                        <div class="card shadow-sm h-100 overflow-hidden">
                                            @if($thumb)
                                                <img src="{{ $thumb }}" alt="" class="card-img-top gallery-img" style="height: 200px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 200px;">
                                                    <i class="bi bi-images fs-1 text-muted"></i>
                                                </div>
                                            @endif
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $cat->nome }}</h5>
                                                @if($cat->descricao)
                                                    <p class="card-text small text-muted mb-0">{{ \Illuminate\Support\Str::limit($cat->descricao, 120) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif

        @if($posts->count() > 0)
            <section class=" bg-light">
                <div class="container">
                    @if(!$categoriaAtiva && $categoriasPortfolio->isNotEmpty())
                        <h4 class="text-nome h5 text-center mb-4">Posts</h4>
                    @endif
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
                                                  @if(Auth::user()->tipo_usuario == 2 && Auth::id() === $usuario->id)
                                                      {{-- BOTÃO EDITAR: AGORA ABRE A MODAL DE EDIÇÃO --}}
                                                      <button type="button" class="btn btn-outline-custom btn-sm"
                                                              data-bs-toggle="modal"
                                                              data-bs-target="#editPostModal"
                                                              data-post-id="{{ $post->id }}"
                                                              data-post-nome="{{ $post->nome }}"
                                                              data-post-descricao="{{ $post->descricao }}"
                                                              data-post-imagens="{{ $post->imagens->map(fn($img) => ['id' => $img->id, 'caminho' => asset('storage/' . $img->caminho_imagem)])->toJson() }}" 
                                                              data-post-categoria="{{ $post->id_categoria_post_portfolio ?? '' }}"
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
        @elseif($categoriaAtiva)
            <div class="container my-4 text-center text-muted">
                <p>Nenhum post nesta categoria ainda.</p>
            </div>
        @endif

            @auth
                @if(Auth::user()->id === $usuario->id && Auth::user()->tipo_usuario == 2 && $portfolio && $totalPostsPortfolio === 0)
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
    @if($usuario->portfolioArtista && $usuario->portfolioArtista->perguntasPropostaContrato->count() > 0)
        {{-- Modal: cadastro solicitante (visitantes) --}}
        <div class="modal fade" id="modalConviteCadastroSolicitante" tabindex="-1" aria-labelledby="modalConviteCadastroSolicitanteLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-nome" id="modalConviteCadastroSolicitanteLabel">Cadastro na plataforma</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Para acompanhar melhor seu orçamento gostaria de se cadastrar como solicitante na plataforma?</p>
                    </div>
                    <div class="modal-footer flex-wrap gap-2">
                        <a href="{{ route('usuarios.createContratante') }}" class="btn btn-outline-custom">Sim</a>
                        <button type="button" class="btn btn-outline-secondary" id="btnConviteCadastroNao">Não</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Proposta de Contrato --}}
        <div class="modal fade p-5 mx-auto modal_proposta" id="modalPropostaContrato" tabindex="-1" role="dialog" aria-labelledby="modalPropostaContratoLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" style="max-width: 60%; margin: auto;">
                <form action="{{ route('propostas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_artista" value="{{ $usuario->portfolioArtista->id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalPropostaContratoLabel">Enviar proposta</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted small">Responda às perguntas definidas pelo profissional que deseja enviar o orçamento.</p>
                            @foreach($usuario->portfolioArtista->perguntasPropostaContrato as $pergunta)
                                <div class="mb-4 border-bottom pb-3">
                                    <label class="form-label fw-semibold">{{ $pergunta->titulo }}</label>
                                    @if($pergunta->tipo === 'texto')
                                        <textarea name="respostas[{{ $pergunta->id }}]" class="form-control" rows="3" required></textarea>
                                    @elseif($pergunta->tipo === 'opcoes')
                                        @foreach($pergunta->opcoesList() as $idx => $opcao)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="respostas[{{ $pergunta->id }}]" id="prop{{ $pergunta->id }}_{{ $idx }}" value="{{ $idx }}" {{ $idx === 0 ? 'required' : '' }}>
                                                <label class="form-check-label" for="prop{{ $pergunta->id }}_{{ $idx }}">{{ $opcao }}</label>
                                            </div>
                                        @endforeach
                                    @elseif($pergunta->tipo === 'anexo')
                                        <input type="file" name="anexos[{{ $pergunta->id }}]" class="form-control" required>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-outline-custom">Enviar proposta</button>
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

                    <div class="mb-3">
                        <label for="edit_id_categoria_post_portfolio" class="form-label">Categoria (opcional)</label>
                        <select name="id_categoria_post_portfolio" id="edit_id_categoria_post_portfolio" class="form-select">
                            <option value="">Sem categoria</option>
                            @foreach(($categoriasPortfolio ?? collect()) as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nome }}</option>
                            @endforeach
                        </select>
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
                        <input class="form-control" type="file" name="imagens[]" id="edit_imagens" multiple accept="image/jpeg,image/png,image/gif,.jpg,.jpeg,.png,.gif">
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
                            <label class="form-label">Categorias que você atua</label>
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

    @auth
        @if(Auth::user()->tipo_usuario == 2 && Auth::id() === $usuario->id && $portfolio)
            <div class="modal fade" id="categoriasPortfolioModal" tabindex="-1" aria-labelledby="categoriasPortfolioModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="categoriasPortfolioModalLabel">Categorias do portfólio</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="small text-muted">Opcional: organize posts por tema. Posts sem categoria aparecem na página principal do portfólio.</p>
                            <h6 class="mt-2">Nova categoria</h6>
                            <form action="{{ route('categorias-posts-portfolio.store') }}" method="POST" class="border rounded p-3 mb-4">
                                @csrf
                                <div class="mb-2">
                                    <label class="form-label">Nome</label>
                                    <input type="text" name="nome" class="form-control" required maxlength="255">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Descrição (opcional)</label>
                                    <textarea name="descricao" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Ordem</label>
                                    <input type="number" name="ordem" class="form-control" value="0">
                                </div>
                                <button type="submit" class="btn btn-outline-custom btn-sm">Adicionar</button>
                            </form>

                            <h6>Categorias existentes</h6>
                            @forelse(($categoriasPortfolio ?? collect()) as $cat)
                                <div class="border rounded p-3 mb-3">
                                    <form action="{{ route('categorias-posts-portfolio.update', $cat) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row g-2 align-items-end">
                                            <div class="col-md-5">
                                                <label class="form-label small">Nome</label>
                                                <input type="text" name="nome" class="form-control form-control-sm" value="{{ $cat->nome }}" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small">Ordem</label>
                                                <input type="number" name="ordem" class="form-control form-control-sm" value="{{ $cat->ordem }}">
                                            </div>
                                            <div class="col-md-3 text-md-end">
                                                <button type="submit" class="btn btn-outline-custom btn-sm">Salvar</button>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label small">Descrição</label>
                                                <textarea name="descricao" class="form-control form-control-sm" rows="2">{{ $cat->descricao }}</textarea>
                                            </div>
                                        </div>
                                    </form>
                                    <form action="{{ route('categorias-posts-portfolio.destroy', $cat) }}" method="POST" class="mt-2" onsubmit="return confirm('Excluir esta categoria? Os posts ficam sem categoria.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Excluir</button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-muted small mb-0">Nenhuma categoria ainda.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth

    {{-- Modal #postModal fica no navbar (Components/navbarbootstrap) — inclui categoria opcional; evita IDs duplicados --}}

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
        const catSel = document.getElementById('edit_id_categoria_post_portfolio');
        if (catSel) {
            catSel.value = button.dataset.postCategoria || '';
        }

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

    (function () {
        var btnNao = document.getElementById('btnConviteCadastroNao');
        if (!btnNao) return;
        btnNao.addEventListener('click', function () {
            var conviteEl = document.getElementById('modalConviteCadastroSolicitante');
            var propostaEl = document.getElementById('modalPropostaContrato');
            if (!conviteEl || !propostaEl) return;
            var conviteModal = bootstrap.Modal.getOrCreateInstance(conviteEl);
            conviteEl.addEventListener('hidden.bs.modal', function onHidden() {
                conviteEl.removeEventListener('hidden.bs.modal', onHidden);
                bootstrap.Modal.getOrCreateInstance(propostaEl).show();
            });
            conviteModal.hide();
        });
    })();
</script>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</body>
</html>