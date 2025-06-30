@extends('Components.navbarbootstrap')
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/perfil.css') }}" rel="stylesheet">
    <title>Minhas Propostas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .card-proposta {
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.03);
        }

        .btn-custom {
            background-color: #7a00ff;
            color: #fff;
            border-radius: 25px;
            padding: 6px 20px;
        }

        .btn-custom-outline {
            border: 2px solid #7a00ff;
            color: #7a00ff;
            border-radius: 25px;
            padding: 6px 20px;
        }

        .status-label {
            font-weight: bold;
            color: #7a00ff;
        }
    </style>
</head>
<body>



<main>




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
        













    <div class="container my-5">
        <h2 class="mb-4 botao_home" style="text-transform: uppercase;">Minhas Propostas</h2>


        
    {{-- Filtros --}}
  <form method="GET" action="{{ route('propostas.minhas') }}" class="row g-3 mb-4">
    <div class="col-md-4">
        <label for="status" class="form-label">Status da Proposta</label>
        <select name="status" id="status" class="form-select">
            <option value="">Todos</option>
            <option value="Aguardando resposta" {{ request('status') == 'Aguardando resposta' ? 'selected' : '' }}>Aguardando resposta</option>
            <option value="Recusada" {{ request('status') == 'Recusada' ? 'selected' : '' }}>Recusada</option>
            <option value="Aguardando execução" {{ request('status') == 'Aguardando execução' ? 'selected' : '' }}>Aguardando execução</option>
            <option value="Finalizada" {{ request('status') == 'Finalizada' ? 'selected' : '' }}>Finalizada</option>
        </select>
    </div>

    @if($usuario->tipo_usuario == 2)
    <div class="col-md-4">
        <label for="avaliador_id" class="form-label">Solicitante</label>
        <select name="avaliador_id" id="avaliador_id" class="form-select">
            <option value="">Todos</option>
            @foreach($avaliadores as $avaliador)
                <option value="{{ $avaliador->id }}" {{ request('avaliador_id') == $avaliador->id ? 'selected' : '' }}>
                    {{ $avaliador->nome }}
                </option>
            @endforeach
        </select>
    </div>
    @endif

    
</form>

<!-- 
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
 -->


        @if($propostas->isEmpty())
            <p class="text-muted">Nenhuma proposta encontrada.</p>
        @else
            @foreach($propostas as $proposta)
                <div class="card-proposta">
                    <h5>{{ $proposta->titulo }}</h5>
                    <p><strong>Descrição:</strong> {{ $proposta->descricao }}</p>
                    <p><strong>Data do serviço:</strong> {{ \Carbon\Carbon::parse($proposta->data)->format('d/m/Y') }}</p>
                    <p><strong>Status:</strong> <span class="status-label">{{ $proposta->status }}</span></p>

                    @if($proposta->motivo)
                        <p><strong>Motivo:</strong> {{ $proposta->motivo }}</p>
                    @endif

                    @if($usuario->tipo_usuario == 2)
                        <p><strong>Solicitante:</strong> {{ $proposta->usuarioAvaliador->nome ?? 'Desconhecido' }}</p>

                        @if($proposta->status === 'Aguardando resposta')
                            <button type="button" class="btn btn-outline-custom btn-sm" data-bs-toggle="modal" data-bs-target="#responderModal{{ $proposta->id }}">
                                Responder Proposta
                            </button>
                        @endif
                    @else
                        <p><strong>Artista:</strong> {{ $proposta->artista->usuario->nome ?? 'Desconhecido' }}</p>
                    @endif
                </div>

                @if($usuario->tipo_usuario == 2 && $proposta->status === 'Aguardando resposta')
                    <!-- Modal de Resposta -->
                    <div class="modal fade p-5 "  id="responderModal{{ $proposta->id }}" tabindex="-1" aria-labelledby="responderModalLabel{{ $proposta->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="responderModalLabel{{ $proposta->id }}">Responder Proposta</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form id="respostaForm{{ $proposta->id }}" action="{{ route('proposta.responder', $proposta->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="motivo{{ $proposta->id }}" class="form-label"> Informe abaixo a justificativa caso recuse o trabalho ou informações relevantes sobre a execução do trabalho caso aceite a proposta . </label>
                                            <textarea class="form-control" id="motivo{{ $proposta->id }}" name="motivo" rows="3"></textarea>
                                        </div>
                                        <input type="hidden" name="status" id="status{{ $proposta->id }}">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-outline-danger" onclick="enviarResposta({{ $proposta->id }}, 'recusada')">Recusar</button>
                                            <button type="button" class="btn btn-custom" onclick="enviarResposta({{ $proposta->id }}, 'aceita')">Aceitar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>

    <!-- Scripts -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
           
        document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const selects = form.querySelectorAll('select');

        selects.forEach(select => {
            select.addEventListener('change', function () {
                form.submit();
            });
        });
    });
       
       
       
       function enviarResposta(propostaId, status) {
            const form = document.getElementById(`respostaForm${propostaId}`);
            const motivoInput = document.getElementById(`motivo${propostaId}`);
            const statusInput = document.getElementById(`status${propostaId}`);
            
            // Remove espaços em branco do início e fim do texto
            const motivo = motivoInput.value.trim();
            
            if (!motivo) {
                alert('Por favor, informe o motivo da sua resposta.');
                motivoInput.focus();
                return;
            }
            
            // Define o status e envia o formulário
            statusInput.value = status;
            form.submit();
        }

        // Foca no textarea quando a modal abrir
        document.addEventListener('DOMContentLoaded', function() {
            const modals = document.querySelectorAll('[id^="responderModal"]');
            modals.forEach(modal => {
                modal.addEventListener('shown.bs.modal', function() {
                    const propostaId = this.id.replace('responderModal', '');
                    const motivoInput = document.getElementById(`motivo${propostaId}`);
                    if (motivoInput) {
                        setTimeout(() => motivoInput.focus(), 100);
                    }
                });
            });
        });


        
    </script>
</main>
@include('Components.footer')

</body>




</html>