
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/cadastro.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


</head>

@include('Components.navbarbootstrap')

<body>
<main class="cadastro-container ">
        <div class="left-illustration">
            <img src="{{ asset('imgs/cadastre.jpg') }}" alt="Ilustração Artista">
        </div>
        
        <div class="form-section" id="formulario-login" >
        <h2>Login</h2>
        @if (session('success'))
            <div style="background-color: #d4edda; color: #155724; padding: 0.75rem; border-radius: 5px; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <label for="email">Email : </label>
            <input type="email" name="email" placeholder="Email" required>

            <label for="senha">Senha : </label>
            <input type="password" name="password" placeholder="Senha" required>

       

            <button type="submit" class="submit-btn">Fazer login</button>
        </form>

        <hr style="margin: 1.5rem 0; border-color: rgba(255, 255, 255, 0.2);">


            <div class="button-group"> 
                <a href="{{ route('usuarios.createArtista') }}">
                     <button type="button" class="cadastro-button">Cadastro Artista</button>
                 </a>

                 <a href="{{ route('usuarios.createContratante') }}">
                     <button type="button" class="cadastro-button ">Cadastro Solicitante</button>
                 </a>
            </div>
    </div>

</main>
</body>
    @include('Components.footer')
 

</html>
