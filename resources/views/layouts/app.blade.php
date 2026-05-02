<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Appolo')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    </head>
 <body>
     <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
         <div class="container">
             <a class="navbar-brand" href="{{ route('categorias-artisticas.index') }}">MeuPortfólio</a>
             <div class="collapse navbar-collapse">
                 <ul class="navbar-nav me-auto">
                     <li class="nav-item">
                         <a class="nav-link" href="{{ route('usuarios.index') }}">Usuários</a>
                     </li>
                     <li class="nav-item">
                         <a class="nav-link" href="{{ route('categorias-artisticas.index') }}">Categorias Artísticas</a>
                     </li>
                 </ul>
                 </div>
         </div>
        
        
         @if(Auth::check())
    <div class="dropdown ms-auto me-3">
        <button class="btn btn-outline-light dropdown-toggle" type="button" id="dropdownProfile" data-bs-toggle="dropdown" aria-expanded="false">
            {{ Auth::user()->nome }}
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownProfile">
        <li><a class="dropdown-item" href="{{ route('usuarios.editInterno') }}">Meu Perfil</a></li>
        
            <li>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="dropdown-item" type="submit">Sair</button>
                </form>
            </li>
        </ul>
    </div>
@endif




     </nav>
 
     <main class="container">
         @yield('content')
     </main>


     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGzKkM4j+1Sxg5b12z4GEF2I2GzWB65p5FuV2c5F5gErc" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-QF1S1F2Hv9V3q0dZx1HOqLzO4PIQlVGxRUHhx+9zV1Wq6v1Xlj2c1wHz0xkBBo5V" crossorigin="anonymous"></script>

     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

