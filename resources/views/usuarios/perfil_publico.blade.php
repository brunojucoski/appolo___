<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Perfil Público</title>
    <link href="{{ asset('css/perfil.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<main>
@include('Components.navbarbootstrap')



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
          var successModal = new bootstrap.Modal(document.getElementById('successModal'));
          successModal.show();
      });
  </script>
@endif

@php
    $portfolio = $usuario->portfolioArtista;
@endphp

 
@if ($errors->any())
      <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="text-nome" id="successModalLabel"> APPOLO </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                     @foreach ($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal"> Fechar </button>
              </div>
          </div>
      </div>
  </div>
        <script>
      document.addEventListener("DOMContentLoaded", function() {
          var successModal = new bootstrap.Modal(document.getElementById('successModal'));
          successModal.show();
      });
  </script>
       
@endif
        




@if ($errors->any())


<div class="modal fade" id="editModalportfolio" tabindex="-1" aria-labelledby="editModalportfolioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $erro)
                <li>{{ $erro }}</li>
            @endforeach
        </ul>
    </div>
    </div>
    </div>
    </div>
@endif






<div class="p-3"> 

<section class="py-5">
    <div class="container" >
      <div class="row align-items-center">
        <div class="col-md-3 text-center text-md-start mb-4 mb-md-0 imagem_perfil">
        <img  src="{{ $usuario->foto_perfil && file_exists(public_path('storage/' . $usuario->foto_perfil)) ? asset('storage/' . $usuario->foto_perfil) : asset('imgs/user.png') }}"  class="rounded-circle border border-4 border-tertiary shadow profile-img" alt="Perfil">
        </div>
        <div class="col-md-9">
          <h1 class="text-nome">{{ $usuario->nome }} </h1>

          @if($usuario->tipo_usuario == 2)
         
          <h3 class="text-nome"> {{ $portfolio->nome_artistico ?? '' }} </h3> 
           
          @endif

          <p class="text-muted"><i class="bi bi-calendar"></i> {{ $usuario->idade }} anos </p>
          <p class="text-muted"><i class="bi bi-geo-alt"></i>  {{ $usuario->cidade ?? 'Localidade não definida' }}  </p>
          
          <p class="text-muted"><i class="bi bi-telephone"></i> {{ formatarTelefone($usuario->telefone) }}</p>
             
          <p><strong>Endereço:</strong> {{ formatarCep($usuario->cep) }} , {{ $usuario->bairro }} , {{ $usuario->endereco }}</p>
        
               @if($usuario->tipo_usuario == 2)
          <i class="bi bi-brush"></i>
                       {{ $portfolio->descricao ?? 'Descrição do portfólio não disponível' }}
                </p>
              
             
                
                <div class="social-icons my-3">

    <a href="{{$portfolio->link_instagram ?? '' }}" class="text-primary fs-4 me-3 text-decoration-none" target="_blank">
      <i class="bi bi-instagram"></i>
    </a>
  
  
    <a href="{{$portfolio->link_behance ?? ''}}" class="text-primary fs-4 me-3" target="_blank">
      <i class="bi bi-link-45deg"></i>
    </a>



</div>
  @endif
             
@auth
    @if(Auth::user()->tipo_usuario == 3 && $usuario->tipo_usuario == 2)
        @if($usuario->portfolioArtista)
            <!-- Botão para contratantes logados, artista com portfólio -->
            <button class="btn btn-sm btn-outline-custom" data-bs-toggle="modal" data-bs-target="#modalPropostaContrato">
                Contratar
            </button>
        @else
            <!-- Artista ainda não criou o portfólio -->
            <button class="btn btn-sm btn-outline-custom" disabled>
                Artista com cadastro incompleto
            </button>
        @endif
    @endif

@else
    <!-- Botão para quem não está logado -->
    <a href="{{ url('/cadastro/contratante') }}" class="btn btn-sm btn-outline-custom">
        Cadastre-se para contratar
    </a>
@endauth
  
<!-- 
          <div class="rating bg-primary bg-opacity-10 d-inline-flex align-items-center px-3 py-1 rounded-pill">
            <span class="fw-bold me-2"> 5 </span>
            <div class="stars">
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
              <i class="bi bi-star-fill text-warning"></i>
            </div>
            <span class="ms-2 text-muted">( ?? avaliações)</span>
          </div>
-->
          @auth
            @if(auth()->user()->id === $usuario->id && auth()->user()->tipo_usuario == 2)
           
                      <button class="btn btn-outline-custom" data-bs-toggle="modal" data-bs-target="#editModalportfolio">
                        <i class="bi bi-pencil"></i>
                        {{ $portfolio ? 'Editar Portfólio' : 'Criar Portfólio' }}
                      </button>
            
            @endif
          @endauth

 <!-- Mostrar as categorias aqui  -->
 
 
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
        </div>
      </div>
    </div>



  </section>
  </div>


@if($usuario->tipo_usuario == 2 && isset($posts) && $posts->count() > 0)
   <div class="p-3 align-itens-center text-center"> 
    <h3 class="text-nome"> Portfólio 
    </h3>
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
                  <img src="{{ asset('storage/' . $img->caminho_imagem) }}" alt="Imagem do post" class="d-block w-100 rounded gallery-img" alt="Imagem do post"
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



        {{-- Modal --}}
        <div class="modal fade" id="modalPost{{ $post->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header border-0">
                <div class="d-flex w-100 justify-content-end gap-2">
                @auth
                @if(Auth::user()->tipo_usuario == 2)
                  <button type="button" class="btn btn-outline-custom btn-sm">
                    <i class="bi bi-pencil"></i> Editar
                  </button>
                  <button type="button" class="btn btn-outline-custom btn-sm">
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

      @else

   @if($usuario->tipo_usuario == 2 && (!isset($posts) || $posts->count() == 0))
  @auth
    @if(Auth::user()->id === $usuario->id && Auth::user()->tipo_usuario == 2)
  <div class="col-12 text-center">
    <div class="card shadow-sm p-4">
      <h4 class="mb-3">Você ainda não tem posts</h4>
      <p class="text-muted">Comece a compartilhar seu trabalho com o mundo!</p>

      <button class="btn btn-outline-custon" data-bs-toggle="modal" data-bs-target="#postModal">
      <i class="bi bi-plus-circle"></i> Faça seu primeiro post
      </button>
    </div>
  </div>
  
     @endif
  @endauth
@endif


  @if ($errors->any())
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $erro)
        <li>{{ $erro }}</li>
      @endforeach
    </ul>
  </div>
@endif





 < <!-- MODAIS DE CADASTRO ABAIXO : -->

 <!-- Modal de Post -->

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

@endif
  @endauth



    </div>
  </div>
</section>






        </div>
    </div>
</div>

@if($usuario->portfolioArtista)
<!-- Modal Proposta de Contrato -->
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


            
  <!-- Modal Editar/Criar Portfolio -->


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

          <!-- Selecionar categorias aqui  -->
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
        <div class="modal-footer">
          <button type="submit" class="btn btn-outline-custom">{{ $portfolio ? 'Salvar Alterações' : 'Criar Portfólio' }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    </main>
</body>

@include('Components.footer')
</html>

