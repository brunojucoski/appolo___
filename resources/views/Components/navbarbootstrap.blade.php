<html> 
  <head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Appolo')</title>
    <link  href="css/navbar.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


</head>
    
<body> 
<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand me-auto " href={{ route('homepage') }}>Appolo</a>
    
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
      <div class="offcanvas-header">

        <a class="offcanvas-title" id="offcanvasNavbarLabel" href={{ route('homepage') }}style="text-decoration: none;"  > Appolo </a>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-left flex-grow-1 pe-3">
  
          <li class="nav-item">
            <a class="nav-link mx-lg-2" aria-current="page" href="{{ route('usuarios.publico') }}">Artistas</a>
          </li>

          <li class="nav-item">
            <a class="nav-link mx-lg-2" href="{{ route('usuarios.contratantes') }}">Solicitantes</a>
          </li>

          <li class="nav-item">
            <a class="nav-link mx-lg-2" href="#">Sobre</a>
          </li>

        </ul>

 <div class="d-flex justify-content-end gap-2 mt-auto position-relative bottom-0 end-0 p-3">
        
  @php
    $user = Auth::user();
  @endphp

@if(auth()->check() && (auth()->user()->tipo_usuario == 2 || auth()->user()->tipo_usuario == 3))
<li class="dropdown dropleft" style="list-style: none;" >
    <a class="position-relative icon_nav "  href="#" id="notificacoesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" onclick="carregarNotificacoes()">
        <i class="bi bi-bell fs-4" ></i>
        @php
            $naoLidas = \App\Models\Notificacao::where('usuario_id', Auth::id())->where('lida', false)->count();
        @endphp
        @if($naoLidas > 0)
            <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-secondary" id="contadorNotificacoes">
                {{ $naoLidas }}
            </span>
        @endif
    </a>
    <ul class="dropdown-menu dropleft" aria-labelledby="notificacoesDropdown" id="listaNotificacoes">
        @php
            $notificacoes = \App\Models\Notificacao::where('usuario_id', Auth::id())->latest()->take(5)->get();
        @endphp
        @forelse($notificacoes as $notificacao)
            <li class="dropdown-item">{{ $notificacao->mensagem }}</li>
        @empty
            <li class="dropdown-item text-muted">Sem notificações</li>
        @endforelse
    </ul>
</li>
@endif
</div>

        
  <div class="d-flex justify-content-end gap-2 mt-auto position-relative bottom-0 end-0 p-3">
 


  @auth

  <div class="actions d-flex gap-2">
    @if(Auth::user()->tipo_usuario == 2)
          <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#postModal">
            <i class="bi bi-plus-circle"></i> Post
          </button>
          @endif



          <button class="btn btn-outline-custom" data-bs-toggle="modal" data-bs-target="#editModal">
            <i class="bi bi-pencil"></i> Editar perfil
          </button>

  <div class="dropdown ms-3">
    <button class="btn btn-primary-custom dropdown-toggle" type="button" id="dropdownProfile" data-bs-toggle="dropdown" aria-expanded="false">
      {{ Auth::user()->nome }}
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownProfile">
    <li><a class="dropdown-item" href="{{ route('usuarios.perfilPublico', Auth::user()->id) }}">Perfil</a></li>
    <li><a class="dropdown-item" href="{{ route('propostas.minhas') }}">Minhas Propostas</a></li>
      <li>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button class="dropdown-item" type="submit">Sair</button>
        </form>
      </li>
    </ul>
  </div>

 

@else
  <li class="botao-nav" id="li-nav"><a class="btn btn-primary-custom ms-3" data-bs-toggle="modal" data-bs-target="#cadastroModal">Cadastrar-se</a></li>
  <li class="botao-nav" id="li-nav"><a class="btn btn-primary-custom ms-3" href="{{ route('login') }}">Entrar</a></li>
@endauth

</div> 

      </div>
    </div>

    

<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
  <span class="navbar-toggler-icon"></span>
</button>

<!-- 
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
</button> -->


    

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


  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title botao_home" style="text-transform: uppercase;" id="editModalLabel">Editar Perfil</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form method="POST" action="{{ route('usuarios.update', Auth::user()->id) }}" enctype="multipart/form-data" class="text-start">
                        @csrf
                        @method('PUT')


                        <div class="mb-3 text-center">
    <label for="foto_perfil" style="cursor: pointer;">
        <img id="previewFotoPerfil"
            src="{{ Auth::user()->foto_perfil ? asset('storage/' . Auth::user()->foto_perfil) : asset('imgs/user.jpg') }}"
            class="rounded-circle shadow"
            alt="Foto de Perfil"
            style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #ccc;"
        >
    </label>
    <input type="file" id="foto_perfil" name="foto_perfil" class="d-none" onchange="previewImagem(event)">
    <div class="form-text">Clique na imagem para alterar sua foto de perfil</div>
</div>

                        <div class="mb-3">
                          <label for="nome" class="form-label">Nome</label>
                           <input type="text" class="form-control" value="{{  Auth::user()->nome }}" name="nome" placeholder="Seu nome completo">
                         </div>
                         <div class="mb-3">
                          <label class="form-label">Telefone</label>
                            <input type="text" name="telefone" class="form-control" value="{{ Auth::user()->telefone }}">
                          </div>
          
            <div class="mb-3">
              <label class="form-label">Gênero</label>
              <div class="d-flex flex-wrap gap-3">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="sexo" id="masculino">
                  <label class="form-check-label" for="masculino">
                    Masculino
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="sexo" id="feminino">
                  <label class="form-check-label" for="feminino">
                    Feminino
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="sexo" id="naoInformar">
                  <label class="form-check-label" for="naoInformar">
                    Não informar
                  </label>
                </div>
              </div>
            </div>
            
            <div class="mb-3">
                            <label class="form-label">CEP</label>
                            <input type="text" name="cep" class="form-control" value="{{  Auth::user()->cep }}">
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

                        <div class="mb-3">
                            <label class="form-label">Nova Senha</label>
                            <input type="password" name="senha" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmar Nova Senha</label>
                            <input type="password" name="senha_confirmation" class="form-control">
                        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary-custom">Confirmar</button>

        </form>
        </div>
      </div>
    </div>
  </div>
  

  <script>
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
    @if(Auth::user()->tipo_usuario == 2)

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

  @endauth

  <!-- Modal de Notificação de Proposta -->
<div class="modal fade" id="modalPropostaNotificacao" tabindex="-1" aria-labelledby="modalPropostaNotificacaoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title botao_home" style="text-transform: uppercase;"id="modalPropostaNotificacaoLabel">Detalhes da Proposta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
     <div class="modal-body">
    <h5 id="propostaTitulo"></h5>
    <p><strong>De:</strong> <span id="propostaAutor"></span></p>
    <p id="propostaDescricao"></p>
    <p><strong>Data do serviço:</strong> <span id="propostaData"></span></p>

    <hr>

    <form id="respostaPropostaForm">
        <div class="mb-3">
            <label for="motivoResposta" class="form-label"> Informe abaixo mais detalhes sobre a prestação do serviço caso aceite ou o motivo caso recuse:</label>
            <textarea class="form-control" id="motivoResposta" name="motivo" rows="3" required></textarea>
        </div>

        <input type="hidden" id="propostaId" name="proposta_id">

        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-outline-custom me-2" onclick="responderProposta('recusada')">Recusar</button>
            <button type="button" class="btn btn-primary-custom" onclick="responderProposta('aceita')">Aceitar</button>
        </div>
    </form>
</div>
    </div>
  </div>
</div>
<script>
  function responderProposta(status) {
      const propostaId = document.getElementById('propostaId').value;
      const motivo = document.getElementById('motivoResposta').value;

      if (!motivo.trim()) {
          alert("Por favor, informe o motivo.");
          return;
      }

      fetch(`/responder-proposta/${propostaId}`, {
          method: "POST",
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
              status: status,
              motivo: motivo
          })
      })
      .then(res => {
          if (!res.ok) {
              return res.json().then(json => Promise.reject(json));
          }
          return res.json();
      })
      .then(data => {
          if (data.success) {
              alert("Resposta registrada com sucesso.");
              location.reload();
          } else {
              alert(data.mensagem || "Ocorreu um erro ao processar a resposta.");
          }
      })
      .catch(error => {
          console.error('Erro ao responder proposta:', error);
          alert(error.mensagem || "Erro ao processar sua resposta.");
      });
  }
</script>


<script>
function carregarNotificacoes() {
    fetch('/notificacoes')
        .then(response => response.json())
        .then(data => {
            const lista = document.getElementById('listaNotificacoes');
            const contador = document.getElementById('contadorNotificacoes');

            lista.innerHTML = ''; // Limpa notificações

            if (data.length === 0) {
                lista.innerHTML = '<li class="dropdown-item text-muted">Nenhuma nova proposta</li>';
                contador.style.display = 'none';
                return;
            }

            contador.innerText = data.length;
            contador.style.display = 'inline-block';


            data.forEach(notificacao => {
    const item = document.createElement('li');
    item.className = 'dropdown-item';
    item.textContent = `${notificacao.proposta.usuario_avaliador.nome} lhe enviou uma proposta de trabalho`;
    item.style.cursor = 'pointer';

    item.addEventListener('click', () => {
    document.getElementById('propostaTitulo').innerText = notificacao.proposta.titulo;
    document.getElementById('propostaAutor').innerText = notificacao.proposta.usuario_avaliador.nome;
    document.getElementById('propostaDescricao').innerText = notificacao.proposta.descricao;
    document.getElementById('propostaData').innerText = new Date(notificacao.proposta.data).toLocaleDateString('pt-BR');
    document.getElementById('propostaId').value = notificacao.proposta.id;

    new bootstrap.Modal(document.getElementById('modalPropostaNotificacao')).show();
});

    lista.appendChild(item);
});
        });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</body> 
    </html> 