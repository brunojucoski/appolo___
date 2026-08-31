<!-- Página que lista todos os artistas cadastrados na plataforma de maneira dinâmica, podendo filtrar pela região e/ou categoria artistica cadastrada -->


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Artistas</title>
    <link href="{{ asset('css/perfil.css') }}" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/artistas-map.css') }}" rel="stylesheet">
</head>
<body>

@include('Components.navbarbootstrap')












@php
    $artistasMapaPayload = ($artistasMapa ?? collect())->map(function ($artista) {
        $portfolio = $artista->portfolioArtista;
        $feedbacks = $portfolio?->feedbacksRecebidos ?? collect();
        $media = $feedbacks->avg('nota');

        return [
            'id' => $artista->id,
            'nome' => $artista->nome,
            'nome_artistico' => $portfolio?->nome_artistico,
            'cidade' => $artista->cidade,
            'bairro' => $artista->bairro,
            'endereco' => $artista->endereco,
            'latitude' => (float) $artista->latitude,
            'longitude' => (float) $artista->longitude,
            'foto' => $artista->foto_perfil ? asset('storage/' . $artista->foto_perfil) : asset('imgs/user.png'),
            'categorias' => $artista->categoriasArtisticas->pluck('nome')->values(),
            'perfil_url' => route('usuarios.perfilPublico', $artista->id),
            'avaliacao_media' => $media ? number_format($media, 1, ',', '.') : null,
            'avaliacao_total' => $feedbacks->count(),
        ];
    })->values();
@endphp

<main>


<div class="container-listagem d-flex"> 
        <link rel="stylesheet" href="{{ asset('css/usuarios_publicos.css') }}"> {{-- opcional --}}

    <div class="container mt-5">
            <form method="GET" action="{{ route('usuarios.publico') }}" class="row g-3 align-items-center mb-4" id="filtroForm">
            
            
                <div class="col-md-4">
                    <select name="categoria" class="form-control" onchange="document.getElementById('filtroForm').submit();">
                        <option value="">Selecione uma área de atuação</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <select name="cidade" class="form-control" onchange="document.getElementById('filtroForm').submit();">
                        <option value="">Todas as cidades</option>
                        @foreach($cidades as $cidade)
                            <option value="{{ $cidade }}" {{ request('cidade') == $cidade ? 'selected' : '' }}>
                                {{ $cidade }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 d-flex justify-content-md-end">
                    <div class="btn-group artistas-view-toggle" role="group" aria-label="Alternar visualização">
                        <input type="radio" class="btn-check" name="visualizacao_artistas" id="visualizarCards" autocomplete="off" checked>
                        <label class="btn btn-outline-custom" for="visualizarCards">
                            <i class="bi bi-grid-3x3-gap"></i> Cards
                        </label>

                        <input type="radio" class="btn-check" name="visualizacao_artistas" id="visualizarMapa" autocomplete="off">
                        <label class="btn btn-outline-custom" for="visualizarMapa">
                            <i class="bi bi-geo-alt"></i> Visualizar em mapa
                        </label>
                    </div>
                </div>
            </form>

                    <section id="artistas-map-view" class="artistas-map-view d-none" aria-label="Mapa de artistas">
                        <div class="artistas-map-heading">
                            <div>
                                <span class="artistas-map-kicker">Mapa de artistas</span>
                                <h2>Profissionais com localização cadastrada</h2>
                            </div>
                            <span class="artistas-map-count">
                                {{ $artistasMapaPayload->count() }} artista{{ $artistasMapaPayload->count() === 1 ? '' : 's' }}
                            </span>
                        </div>

                        <div class="artistas-map-container">
                            <div id="artistas-map" class="artistas-map" data-map-empty="{{ $artistasMapaPayload->isEmpty() ? '1' : '0' }}"></div>
                            <div class="artistas-map-empty {{ $artistasMapaPayload->isEmpty() ? '' : 'd-none' }}" data-map-empty-message>
                                Nenhum artista com localização cadastrada foi encontrado para os filtros atuais.
                            </div>
                        </div>
                    </section>

                    <div id="artistas-list-view">
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
</div> 

</main>

@include('Components.footer')


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="application/json" id="artistas-map-data">@json($artistasMapaPayload)</script>
<script src="{{ asset('js/artistas-map.js') }}"></script>
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
