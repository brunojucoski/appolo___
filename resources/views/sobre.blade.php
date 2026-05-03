

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

              <img src="imgs/sobre.png" alt="Background" class="img-fluid" style="max-height: 1000px;" />
            </div>


        <div class="col-md-6 text-center align-items-center ">

      

          <h2 class=" mb-4 text-sobre">
           <br> 
            O MeuPortfólio é uma plataforma de conexão entre artistas e solicitantes, que visa facilitar a busca e a contratação de serviços. A fim de dar visibilidade para profissionais do setor cultural. O projeto é sem fins lucrativos e é desenvolvido por @cajutatueiro para a comunidade ... Porém aceito investimentos hehe tenho mais ideias para implementar também inclusive. 
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
            <br>
          </h2>
 
        </div>
       

      </div>
    </div>
  </section>
</main>

@include('Components.footer')

</body> 