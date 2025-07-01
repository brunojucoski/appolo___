<!-- Página que lista todos os artistas cadastrados na plataforma de maneira dinâmica, podendo filtrar pela região e/ou categoria artistica cadastrada -->


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


<div class="container-listagem d-flex"> 
        <link rel="stylesheet" href="{{ asset('css/usuarios_publicos.css') }}"> {{-- opcional --}}

    <div class="container mt-5">
            <form method="GET" action="{{ route('usuarios.publico') }}" class="row mb-4 " id="filtroForm">
            
            
                <div class="col-md-5 w-50">
                    <select name="categoria" class="form-control" onchange="document.getElementById('filtroForm').submit();">
                        <option value="">Todas as categorias</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5 w-50">
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

                    <div id="lista-usuarios">
                        @include('partials.lista_usuarios', ['usuarios' => $usuarios])
                    </div>

                <div class="text-center mt-4 p-3">
                    @if ($usuarios->hasMorePages())
                        <button id="load-more" class="btn btn-outline-custom" data-next-page="{{ $usuarios->currentPage() + 1 }}">
                            +
                        </button>
                    @endif
                </div>
        
    </div>
</div> 

</main>

@include('Components.footer')


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#load-more').on('click', function () {
        var button = $(this);
        var nextPage = button.data('next-page');
        var url = "{{ route('usuarios.publico') }}" + '?page=' + nextPage;

        // Adiciona filtros ativos na URL (se houver)
        var form = $('#filtroForm');
        var categoria = form.find('select[name="categoria"]').val();
        var cidade = form.find('select[name="cidade"]').val();
        if (categoria) url += '&categoria=' + categoria;
        if (cidade) url += '&cidade=' + cidade;

        $.get(url, function (data) {
            $('#lista-usuarios').append(data);

            // Atualiza número da próxima página
            button.data('next-page', nextPage + 1);

            // Se não tiver mais páginas, remove o botão
            if (data.trim() === '') {
                button.remove();
            }
        });
    });
</script>



</body> 