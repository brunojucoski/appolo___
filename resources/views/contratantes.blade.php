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




        
















<div class="container-listagem"> 
<link rel="stylesheet" href="{{ asset('css/usuarios_publicos.css') }}"> {{-- opcional --}}

<div class="container mt-5">
   <form method="GET" action="{{ route('usuarios.contratantes') }}" class="row mb-4 " id="filtroForm">

        <div class="col-md-5 w-100">
         
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

    @foreach ($usuarios as $usuario)
        <div class="card mb-4 p-3 shadow-sm">
            <div class="row align-items-center">
                <div class="col-auto imagem-centralizada-mobile p-3">
                  <img src="{{ $usuario->foto_perfil ? asset('storage/' . $usuario->foto_perfil) : asset('imgs/user.png') }}" class="rounded-circle" width="100" height="100">
                </div>
                <div class="col-md-8">
                    <h5 class="text-nome">{{ $usuario->nome }}</h5>
                    <p class="mb-1">
                        {{ \Carbon\Carbon::parse($usuario->data_nasc)->age }} anos<br>
                        {{ $usuario->cidade ?? 'Cidade não informada' }}
                    </p>
                  
                    <div class="mt-1">
                  
                        <strong>5 </strong> ⭐ 
                        
                    </div>
                </div>
                <div class="col-md-2 text-end">
                    <a href="{{ route('usuarios.perfilPublico', $usuario->id) }}" class="btn btn-purple">Ver perfil</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
</div>
</div> 




</body> 