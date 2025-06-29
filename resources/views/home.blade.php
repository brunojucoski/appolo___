<!-- Página home da aplicação, mostrando  um panorama geral sobre o que se trata a plataforma -->
@extends('Components.navbarbootstrap')

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link  href="css/home.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  
<head> 

<body> 







<main>
  <section class="hero-section d-flex align-items-center">
    <div class="container p-5">
      <div class="row align-items-center">
        <div class="col-md-6 text-center align-items-center ">
          <h2 class="hero-text mb-4">
           <strong style="text-transform: uppercase;"> Junte-se à nossa comunidade e transforme a cultura! </strong> <br> 
            Conecte talentos a oportunidades e ajude a construir uma sociedade mais viva, justa e acessível para todos.
          </h2>
          <a class="btn btn-primary-custom big-btn mx-auto w-auto botao_home"  href="{{ route('usuarios.publico') }}"> BUSQUE PROFISSIONAIS </a>
        </div>
        <div class="col-md-6 text-center">
          <img src="imgs/banner.jpg" alt="Background" class="img-fluid" style="max-height: 700px;" />
        </div>


      </div>
    </div>
  </section>
</main>

@include('Components.footer')

</section> 


</body>



<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

</head> 