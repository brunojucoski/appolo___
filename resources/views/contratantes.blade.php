<!-- página que lista os contratantes que mais realizaram interações na plataforma -->

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Artistas</title>
    <link href="{{ asset('css/perfil.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

@include('Components.navbarbootstrap')


<main>

        



<div class="container-listagem"> 
    <link rel="stylesheet" href="{{ asset('css/usuarios_publicos.css') }}"> {{-- opcional --}}

    <div class="container mt-5">
        <form method="GET" action="{{ route('usuarios.contratantes') }}" class="row mb-4 " id="filtroForm">

                        
                            
                            <div class="col-md-5 w-100">
                                <select name="cidade" class="form-control" onchange="document.getElementById('filtroForm').submit();">
                                    <option value="">Todas as cidades</option>
                                    @foreach($cidades as $cidade)
                                        <option value="{{ $cidade }}" {{ request('cidade') == $cidade ? 'selected' : '' }}>
                                            {{ $cidade }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
        </form>
                        
            <div class="container p-3"> 

                        <div id="lista-contratantes">
                            @include('partials.lista_contratantes', ['usuarios' => $usuarios])
                        </div>

            </div>
                    
            <div class="text-center mt-3 p-3">
                        <button id="btn-carregar-mais" class="btn btn-outline-custom" data-url="{{ $usuarios->nextPageUrl() }}">
                            +
                        </button>
            </div>
    </div>
</div> 

</main>


@include('Components.footer')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btn = document.getElementById('btn-carregar-mais');

        btn?.addEventListener('click', function () {
            let url = this.dataset.url;
            if (!url) return;

            // Adiciona filtros ativos (como cidade) na URL
            const form = document.getElementById('filtroForm');
            const cidadeSelect = form.querySelector('select[name="cidade"]');
            const cidade = cidadeSelect?.value;

            const urlObj = new URL(url, window.location.origin);
            if (cidade) {
                urlObj.searchParams.set('cidade', cidade);
            }

            fetch(urlObj.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.html) {
                        document.getElementById('lista-contratantes').insertAdjacentHTML('beforeend', data.html);
                    }

                    if (data.next_page_url) {
                        btn.dataset.url = data.next_page_url;
                    } else {
                        btn.remove(); // remove botão se não há mais páginas
                    }
                })
                .catch(err => console.error('Erro ao carregar mais contratantes:', err));
        });
    });
</script>

</body> 