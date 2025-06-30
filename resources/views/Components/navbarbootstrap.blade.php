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
            <a class="nav-link mx-lg-2" href="{{ route('sobrepage') }}">Sobre</a>
          </li>

        </ul>

 <div class="d-flex justify-content-end gap-2 mt-auto position-relative bottom-0 end-0 p-3">
        
  @php
    $user = Auth::user();
  @endphp

@if(auth()->check() && (auth()->user()->tipo_usuario == 2 || auth()->user()->tipo_usuario == 3))
<li class="dropdown dropleft" style="list-style: none;">
    <a class="position-relative icon_nav" href="#" id="notificacoesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" onclick="carregarNotificacoes()">
        <i class="bi bi-bell fs-4"></i>
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
            <a href="{{ route('propostas.minhas') }}" class="dropdown-item">
                {{ $notificacao->mensagem }}
            </a>
        @empty
            <span class="dropdown-item text-muted">Sem notificações</span>
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



          <button class="btn btn-outline-custom" data-bs-toggle="offcanvas" data-bs-target="#editOffcanvas">
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


  <div class="offcanvas offcanvas-end" tabindex="-1" id="editOffcanvas" aria-labelledby="editOffcanvasLabel">
        <div class="offcanvas-header">
            <h3 class="botao_home text-uppercase" id="editOffcanvasLabel">Editar Perfil</h3>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
        </div>

        <div class="offcanvas-body">
        
     


        
        <form method="POST" action="{{ route('usuarios.update', Auth::user()->id) }}" enctype="multipart/form-data" class="text-start">
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
    <input type="file" id="foto_perfil" name="foto_perfil" class="d-none" onchange="previewImagem(event)">
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
                            <input type="text" name="cep" onblur="buscarCEP(this.value)" onkeydown="if(event.key === 'Enter'){ event.preventDefault(); }" class="form-control" value="{{  Auth::user()->cep }}" maxlength="9" inputmode="numeric">
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
                    <div class="modal-footer p-3">
                      <button type="button" class="btn btn-outline-custom me-2" data-bs-dismiss="offcanvas">Cancelar</button>
                      <button type="submit" class="btn btn-primary-custom">Confirmar</button>
                    </div>
              </form>
                    
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




<script>
function carregarNotificacoes() {
    fetch('/notificacoes')
        .then(response => response.json())
        .then(data => {
            const lista = document.getElementById('listaNotificacoes');
            const contador = document.getElementById('contadorNotificacoes');

            lista.innerHTML = '';

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
                fetch(`/notificacoes/${notificacao.id}/marcar-lida`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                }).then(() => {
                    window.location.href = "{{ route('propostas.minhas') }}";
                });
            });

    lista.appendChild(item);
});
        });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const cepInput = document.querySelector('input[name="cep"]');
    const telefoneInput = document.querySelector('input[name="telefone"]');

    // Máscara de CEP
    cepInput.addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');

        if (value.length > 5) {
            value = value.slice(0, 5) + '-' + value.slice(5, 8);
        }

        e.target.value = value;
    });

    // Máscara de Telefone (formato: (00)00000-0000)
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
});
</script>


   
<script>

function buscarCEP(cep) {

    console.log(`https://viacep.com.br/ws/${cep}/json/`)

 fetch(`https://viacep.com.br/ws/${cep}/json/`)
    .then(response => {
        if (!response.ok) {
            throw new Error("Erro ao buscar o CEP.");
        }
        return response.json();
    })
    .then(data => {
        if (data.erro) {
            alert("CEP não encontrado.");
            return;
        }

        const form = document.querySelector('#editOffcanvas form');
        if (form) {
            form.querySelector('input[name="cidade"]').value = data.localidade || '';
            form.querySelector('input[name="bairro"]').value = data.bairro || '';
            form.querySelector('input[name="endereco"]').value = data.logradouro || '';
        }
    })
    .catch(error => {
        console.error(error);
        alert("Erro ao consultar o CEP.");
    });
        
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
                        texto = `Avalie o artista "${proposta.artista.nome_artistico}" sobre a proposta "${proposta.titulo}" executada na data ${new Date(proposta.data).toLocaleDateString()}`;
                    } else {
                        texto = `Avalie o contratante "${proposta.usuario_avaliador.nome}" sobre a proposta "${proposta.titulo}" executada na data ${new Date(proposta.data).toLocaleDateString()}`;
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

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</body> 
    </html> 