

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
  <section class="hero-section d-flex align-items-center">
    <div class="container p-5">
      <div class="row align-items-center" style="padding-top:100px">
           
            <div class="col-md-6 text-center ">

              <img src="imgs/sobre.jpg" alt="Background" class="img-fluid" style="max-height: 1000px;" />
            </div>


        <div class="col-md-6 text-center align-items-center ">

      

          <h2 class=" mb-4 text-sobre">
           <br> 
            Somos uma equipe de formandos do curso de Análise e Desenvolvimento de Sistemas da instituição SENAC, que através do Projeto Integrador de conclusão de curso, desenvolvemos a Appolo, a fim de criar uma sociedade mais justa e inclusiva! 
          </h2>
 
        </div>
       

      </div>
    </div>
  </section>
</main>

@include('Components.footer')

</body> 