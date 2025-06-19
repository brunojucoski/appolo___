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


<div class="container-listagem"> 
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

    @foreach ($usuarios as $usuario)
        <div class="card mb-4 p-3 shadow-sm">
            <div class="row align-items-center">
                <div class="col-auto">
                <img src="{{ $usuario->foto_perfil ? asset('storage/' . $usuario->foto_perfil) : asset('imgs/user.png') }}" class="rounded-circle" width="100" height="100">
                </div>
                <div class="col-md-8">
                    <h5>{{ $usuario->nome }}</h5>
                    <p class="mb-1">
                        {{ \Carbon\Carbon::parse($usuario->data_nasc)->age }} anos<br>
                        {{ $usuario->cidade ?? 'Cidade não informada' }}
                    </p>
                    <p class="mb-0">
                        {{ $usuario->categoriasArtisticas->pluck('nome')->implode(', ') }}
                    </p>
                    <div class="mt-1">
                  
                        <strong>5 </strong> ⭐ 
                        
                    </div>
                </div>
                <div class="col-md-2 text-end">
                    <a href="{{ route('usuarios.perfilPublico', $usuario->id) }}" class="btn btn-purple btn-outline-custom">Ver perfil</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
</div> 




</body> 