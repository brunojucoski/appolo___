<html> 
  <head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Appolo')</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    <link href="{{ asset('css/endereco-map.css') }}" rel="stylesheet">


</head>
    
<body> 


@if(session('success'))
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="text-nome" id="successModalLabel"> MeuPortfólio </h5>
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


<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand me-auto" href="{{ route('homepage') }}">MeuPortfólio</a>

        @if(auth()->check() && (auth()->user()->tipo_usuario == 2 || auth()->user()->tipo_usuario == 3))
        <ul class="navbar-nav d-lg-none order-lg-last"> <li class="nav-item dropdown">
                <a class="nav-link position-relative" href="#" id="notificacoesDropdownMobile" role="button" data-bs-toggle="dropdown" aria-expanded="false" onclick="carregarNotificacoes()">
                    <i class="bi bi-bell fs-4"></i>
                    @php
                        $naoLidas = \App\Models\Notificacao::where('usuario_id', Auth::id())->where('lida', false)->count();
                    @endphp
                    @if($naoLidas > 0)
                        <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-secondary" id="contadorNotificacoesMobile">
                            {{ $naoLidas }}
                        </span>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificacoesDropdownMobile" id="listaNotificacoesMobile">
                    </ul>
            </li>
        </ul>
        @endif

        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <a class="offcanvas-title" id="offcanvasNavbarLabel" href="{{ route('homepage') }}" style="text-decoration: none;"> MeuPortfólio </a>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-left flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2" aria-current="page" href="{{ route('usuarios.publico') }}">Buscar portfólios</a>
                    </li>
               <!--     <li class="nav-item">
                        <a class="nav-link mx-lg-2" href="{{ route('usuarios.contratantes') }}">Solicitantes</a>
                    </li>
                    -->
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2" href="{{ route('sobrepage') }}">Sobre</a>
                    </li>
                    
                </ul>

                <ul class="navbar-nav d-none d-lg-flex ms-auto align-items-center">
                    @auth
                        @if(Auth::user()->tipo_usuario == 2 || Auth::user()->tipo_usuario == 3)
                        <li class="nav-item dropdown me-2"> <a class="nav-link position-relative" href="#" id="notificacoesDropdownDesktop" role="button" data-bs-toggle="dropdown" aria-expanded="false" onclick="carregarNotificacoes()">
                                <i class="bi bi-bell fs-4"></i>
                                @php
                                    $naoLidas = \App\Models\Notificacao::where('usuario_id', Auth::id())->where('lida', false)->count();
                                @endphp
                                @if($naoLidas > 0)
                                    <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-secondary" id="contadorNotificacoesDesktop">
                                        {{ $naoLidas }}
                                    </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificacoesDropdownDesktop" id="listaNotificacoesDesktop">
                                </ul>
                        </li>
                        @endif

                        @if(Auth::user()->tipo_usuario == 2)
                      <li class="nav-item me-2">
                          <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#postModal" data-bs-dismiss="offcanvas">
                              <i class="bi bi-plus-circle"></i> Post
                          </button>
                      </li>
                        @endif
                        <li class="nav-item me-2"> <button class="btn btn-outline-custom" data-bs-toggle="offcanvas" data-bs-target="#editOffcanvas">
                                <i class="bi bi-pencil"></i> Editar perfil
                            </button>
                        </li>
                        <li class="nav-item dropdown">
                            <button class="btn btn-primary-custom dropdown-toggle" type="button" id="dropdownProfile" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->nome }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownProfile">
                                <li><a class="dropdown-item" href="{{ route('usuarios.perfilPublico', Auth::user()->id) }}">Perfil</a></li>
                                @if(Auth::user()->tipo_usuario == 2 && Auth::user()->portfolioArtista)
                                    <li><a class="dropdown-item" href="{{ route('perguntas-proposta.index') }}">Formulário de orçamento</a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('propostas.minhas') }}">Meus orçamentos</a></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item" type="submit">Sair</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item me-2"> <a class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#cadastroModal">Cadastrar-se</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary-custom" href="{{ route('login') }}">Entrar</a>
                        </li>
                    @endauth
                </ul>

                <ul class="navbar-nav d-lg-none mt-3">
                    @auth
                        @if(Auth::user()->tipo_usuario == 2)
                     <li class="nav-item mb-2">
                          <button class="btn btn-primary-custom w-100" data-bs-toggle="modal" data-bs-target="#postModal" data-bs-dismiss="offcanvas">
                              <i class="bi bi-plus-circle"></i> Post
                          </button>
                      </li>
                        @endif
                        <li class="nav-item mb-2">
                            <button class="btn btn-outline-custom w-100" data-bs-toggle="offcanvas" data-bs-target="#editOffcanvas">
                                <i class="bi bi-pencil"></i> Editar perfil
                            </button>
                        </li>
                        <li class="nav-item dropdown">
                            <button class="btn btn-primary-custom dropdown-toggle w-100" type="button" id="dropdownProfileOffcanvas" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->nome }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end w-100" aria-labelledby="dropdownProfileOffcanvas">
                                <li><a class="dropdown-item" href="{{ route('usuarios.perfilPublico', Auth::user()->id) }}">Perfil</a></li>
                                @if(Auth::user()->tipo_usuario == 2 && Auth::user()->portfolioArtista)
                                    <li><a class="dropdown-item" href="{{ route('perguntas-proposta.index') }}">Formulário de orçamento</a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('propostas.minhas') }}">Meus orçamentos</a></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button class="dropdown-item" type="submit">Sair</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item mb-2">
                            <a class="btn btn-primary-custom w-100" data-bs-toggle="modal" data-bs-target="#cadastroModal">Cadastrar-se</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary-custom w-100" href="{{ route('login') }}">Entrar</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>
</nav>


<div class="modal fade" id="cadastroModal" tabindex="-1" aria-labelledby="cadastroModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">

      <div class="modal-body button-group" >

      <li class="botao-nav" id="li-nav"><a class="btn btn-primary-custom ms-3" href="{{ route('usuarios.createArtista') }}">Cadastro Artista</a></li>
      <li class="botao-nav" id="li-nav"><a class="btn btn-primary-custom ms-3" href="{{ route('usuarios.createContratante') }}">Cadastro Solicitante</a></li>

      </div>
      </div>
    </div>
  </div>



@auth


  <div class="offcanvas offcanvas-end" tabindex="-1" id="editOffcanvas" aria-labelledby="editOffcanvasLabel">
        <div class="offcanvas-header">
            <h3 class="botao_home text-uppercase" id="editOffcanvasLabel">Editar Perfil</h3>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
        </div>

        <div class="offcanvas-body">
        
     


        
        <form method="POST" action="{{ route('usuarios.update', Auth::user()->id) }}" enctype="multipart/form-data" class="text-start" data-address-form>
                        @csrf
                        @method('PUT')


                        <div class="mb-3 text-center">
    <label for="foto_perfil" style="cursor: pointer;">
        <img id="previewFotoPerfil"
            src="{{ Auth::user()->foto_perfil ? asset('storage/' . Auth::user()->foto_perfil) : asset('imgs/user.png') }}"
            class="rounded-circle shadow"
            alt="Foto de Perfil"
            style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #ccc;"
        >
    </label>
    <input type="file" id="foto_perfil" name="foto_perfil" class="d-none" accept="image/jpeg,image/png,image/gif,.jpg,.jpeg,.png,.gif" onchange="previewImagem(event)">
    <div class="form-text">Clique na imagem para alterar sua foto de perfil</div>
    </div>

                        <div class="mb-3">
                          <label for="nome" class="form-label">Nome</label>
                           <input type="text" class="form-control" value="{{  Auth::user()->nome }}" name="nome" placeholder="Seu nome completo">
                         </div>
                         <div class="mb-3">
                          <label class="form-label">Telefone</label>
                            <input type="text" name="telefone" class="form-control" value="{{ Auth::user()->telefone }}" maxlength="15" inputmode="numeric">
                          </div>
          
           <div class="mb-3">
                <label class="form-label">Gênero</label>
                  <div class="d-flex flex-wrap gap-3">
            @foreach($generos as $genero)
                          <div class="form-check">
                            <input class="form-check-input" type="radio" name="sexo_usuario" id="genero{{ $genero->id }}" value="{{ $genero->id }}"
                                     {{ Auth::user()->sexo_usuario == $genero->id ? 'checked' : '' }}>
                            <label class="form-check-label" for="genero{{ $genero->id }}">
                                      {{ $genero->nome }}
                  </label>
                          </div>
            @endforeach
                 </div>
          </div>
            
            <div class="mb-3">
                            <label class="form-label">CEP</label>
                            <input type="text" name="cep" onkeydown="if(event.key === 'Enter'){ event.preventDefault(); }" class="form-control" value="{{  Auth::user()->cep }}" maxlength="9" inputmode="numeric">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cidade</label>
                            <input type="text" name="cidade" class="form-control" value="{{  Auth::user()->cidade }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bairro</label>
                            <input type="text" name="bairro" class="form-control" value="{{ Auth::user()->bairro }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Endereço</label>
                            <input type="text" name="endereco" class="form-control" value="{{  Auth::user()->endereco }}">
                        </div>

                        <input type="hidden" name="latitude" value="{{ Auth::user()->latitude }}">
                        <input type="hidden" name="longitude" value="{{ Auth::user()->longitude }}">

                        <div class="mb-3">
                            <label class="form-label">Localização no mapa</label>
                            <div class="address-map-card">
                                <div class="address-map" data-address-map></div>
                                <p class="address-map-help">Clique no mapa ou arraste o ponto para ajustar a localização.</p>
                            </div>
                            <div class="address-map-status mt-1" data-address-status></div>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-custom w-100 d-flex align-items-center justify-content-center gap-2" data-bs-dismiss="offcanvas" data-bs-toggle="modal" data-bs-target="#modalRedefinirSenha">
                                <i class="bi bi-key"></i> Redefinir senha
                            </button>
                        </div>

                    <div class="modal-footer p-3 border-0">
                      <button type="button" class="btn btn-outline-custom me-2" data-bs-dismiss="offcanvas">Cancelar</button>
                      <button type="submit" class="btn btn-primary-custom">Confirmar</button>
                    </div>
              </form>
                    
      </div>
    </div>

  <div class="modal fade" id="modalRedefinirSenha" tabindex="-1" aria-labelledby="modalRedefinirSenhaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title botao_home" id="modalRedefinirSenhaLabel">Redefinir senha</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        <form method="POST" action="{{ route('usuarios.password.update') }}">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <p class="text-muted small">Use pelo menos 8 caracteres.</p>
            <div class="mb-3">
              <label for="redefinir_senha_nova" class="form-label">Nova senha</label>
              <input type="password" name="senha" id="redefinir_senha_nova" class="form-control" required minlength="8" autocomplete="new-password">
            </div>
            <div class="mb-3">
              <label for="redefinir_senha_confirma" class="form-label">Confirmar nova senha</label>
              <input type="password" name="senha_confirmation" id="redefinir_senha_confirma" class="form-control" required minlength="8" autocomplete="new-password">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary-custom">Salvar senha</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @auth
    @if(Auth::user()->tipo_usuario == 2)
        @php
            $navPortfolio = Auth::user()->portfolioArtista;
            $navCategoriasPostPortfolio = $navPortfolio
                ? $navPortfolio->categoriasPostsPortfolio
                : collect();
        @endphp

  <div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title  botao_home" id="postModalLabel" style="text-transform: uppercase;">Novo Post</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
              @csrf


              <div class="mb-3">
                <label for="post_modal_nome" class="form-label">Título</label>
                <input type="text" class="form-control" name="nome" id="post_modal_nome" placeholder="Título do seu post">
              </div>


              <div class="mb-3">
                <label for="post_modal_descricao" class="form-label">Descrição</label>
                <textarea class="form-control" name="descricao" id="post_modal_descricao" rows="3" placeholder="Descreva sua obra"></textarea>
              </div>

              @if($navPortfolio)
              <div class="mb-3">
                <label for="post_modal_id_categoria" class="form-label">Publicar em categoria <span class="text-muted fw-normal">(opcional)</span></label>
                <select name="id_categoria_post_portfolio" id="post_modal_id_categoria" class="form-select">
                  <option value="">Sem categoria — aparece na página principal do portfólio</option>
                  @foreach($navCategoriasPostPortfolio as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->nome }}</option>
                  @endforeach
                </select>
              </div>
              @endif


              <div class="mb-3">
                <label for="post_modal_imagens" class="form-label">Imagens</label>
                <input class="form-control" type="file" name="imagens[]" id="post_modal_imagens" multiple accept="image/jpeg,image/png,image/gif,.jpg,.jpeg,.png,.gif">
              </div>

            

              
              <div class="modal-footer px-0 pb-0">
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

  @endauth




<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/endereco-map.js') }}"></script>

<script>
    function carregarNotificacoes() {
        fetch('/notificacoes')
            .then(response => response.json())
            .then(data => {
                // Atualiza o dropdown de desktop
                const listaDesktop = document.getElementById('listaNotificacoesDesktop');
                const contadorDesktop = document.getElementById('contadorNotificacoesDesktop');
                updateNotificationDropdown(listaDesktop, contadorDesktop, data);

                // Atualiza o dropdown de mobile
                const listaMobile = document.getElementById('listaNotificacoesMobile');
                const contadorMobile = document.getElementById('contadorNotificacoesMobile');
                updateNotificationDropdown(listaMobile, contadorMobile, data);
            })
            .catch(error => console.error("Erro ao buscar notificações:", error));
    }

    // Função auxiliar para atualizar o conteúdo do dropdown
    function updateNotificationDropdown(listaElement, contadorElement, notificacoesData) {
        listaElement.innerHTML = ''; // Limpa o conteúdo atual

        if (notificacoesData.length === 0) {
            listaElement.innerHTML = '<li class="dropdown-item text-muted">Nenhuma nova proposta</li>';
            if (contadorElement) { // Verifica se o contador existe
                contadorElement.style.display = 'none';
            }
            return;
        }

        if (contadorElement) { // Verifica se o contador existe
            contadorElement.innerText = notificacoesData.length;
            contadorElement.style.display = 'inline-block';
        }

        notificacoesData.forEach(notificacao => {
            const item = document.createElement('li');
            const link = document.createElement('a'); // Criar um <a> dentro do <li>
            link.className = 'dropdown-item';
            link.textContent = `${notificacao.proposta.usuario_avaliador.nome} lhe enviou uma proposta de trabalho`;
            link.href = "{{ route('propostas.minhas') }}"; // Link para a página de propostas

            link.addEventListener('click', (event) => {
                event.preventDefault(); // Evita a navegação imediata
                fetch(`/notificacoes/${notificacao.id}/marcar-lida`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                }).then(() => {
                    window.location.href = "{{ route('propostas.minhas') }}"; // Redireciona após marcar como lida
                });
            });
            item.appendChild(link); // Adiciona o link ao item da lista
            listaElement.appendChild(item);
        });
    }

    // Chama a função carregarNotificacoes() no carregamento da página para garantir que os contadores estejam atualizados
//    document.addEventListener('DOMContentLoaded', carregarNotificacoes);
</script>

<script>
    // scripts de CEP, telefone, preview de imagem, feedback, etc. ...



document.addEventListener('DOMContentLoaded', function () {
    const telefoneInput = document.querySelector('input[name="telefone"]');

    // Máscara de Telefone (formato: (00)00000-0000)
    if (telefoneInput) {
      telefoneInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, ''); // remove tudo que não for número

        if (value.length > 11) value = value.slice(0, 11); // limita a 11 dígitos

        if (value.length >= 2) {
            value = '(' + value.slice(0, 2) + ')' + value.slice(2);
        }

        if (value.length > 8) {
            value = value.slice(0, 9) + '-' + value.slice(9);
        }

        e.target.value = value;
      });

      telefoneInput.setAttribute('maxlength', '15'); // (00)00000-0000 tem 14 caracteres
    }
});


//preview imagem ao editar perfil 

    function previewImagem(event) {
        const input = event.target;
        const preview = document.getElementById('previewFotoPerfil');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

</script>






@auth
<div class="modal fade" id="feedbackModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" action="{{ Auth::user()->tipo_usuario == 3 ? route('feedbacks.artistas.store') : route('feedbacks.contratantes.store') }}">
      @csrf
      <input type="hidden" name="id_proposta" id="idPropostaFeedback">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Avalie sua experiência</h5>
        </div>
        <div class="modal-body text-center">
          <p id="feedbackInfo" class="mb-3 text-muted"></p>
          <div id="estrelas" class="mb-3">
              @for ($i = 1; $i <= 5; $i++)
                  <i class="bi bi-star star" data-value="{{ $i }}"></i>
              @endfor
              <input type="hidden" name="nota" id="notaEstrela" required>
          </div>
          <textarea name="comentario" class="form-control" placeholder="Deixe um comentário..." required></textarea>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endauth

<style>
  .star {
    font-size: 2rem;
    color: #ccc;
    cursor: pointer;
  }
  .star.selecionada {
    color: #FFD700;
  }
</style>



<script>
  document.addEventListener('DOMContentLoaded', function () {
    let estrelas = document.querySelectorAll('.star');
    estrelas.forEach(estrela => {
      estrela.addEventListener('click', function () {
        let valor = this.getAttribute('data-value');
        document.getElementById('notaEstrela').value = valor;

        estrelas.forEach(s => {
          s.classList.remove('selecionada');
          if (s.getAttribute('data-value') <= valor) {
            s.classList.add('selecionada');
          }
        });
      });
    });
  });
</script>



@auth
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const tipoUsuario = {{ auth()->user()->tipo_usuario }};
        const rota = tipoUsuario === 2 
            ? "/feedbacks/pendentes/contratantes"
            : "/feedbacks/pendentes/artistas";

        fetch(rota)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const proposta = data[0];
                    document.getElementById('idPropostaFeedback').value = proposta.id;

                    // Preenche texto com nome artístico ou nome do contratante
                    let texto = '';
                    if (tipoUsuario === 3) {
                        texto = `Avalie o artista "${proposta.artista?.nome_artistico ?? ''}" sobre a proposta #${proposta.id}.`;
                    } else {
                        texto = `Avalie o contratante "${proposta.usuario_avaliador?.nome ?? ''}" sobre a proposta #${proposta.id}.`;
                    }

                    document.getElementById('feedbackInfo').innerText = texto;

                    const modal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                    modal.show();
                }
            })
            .catch(error => console.error("Erro ao buscar feedbacks pendentes:", error));
    });
</script>
@endauth

<script>
  document.addEventListener('hidden.bs.modal', function () {
      // Força a remoção de "overflow: hidden" do body
      document.body.style.overflow = 'auto';
  });
</script>




<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body> 
    </html> 
