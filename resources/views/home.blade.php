{{-- Página inicial — panorama da plataforma (estrutura alinhada ao restante do sistema) --}}
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>MeuPortfólio — Início</title>
    <link href="{{ asset('css/navbar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
</head>
<body>

@include('Components.navbarbootstrap')

<main class="home-page-main">

    {{-- HERO --}}
    <section class="home-hero py-5 position-relative overflow-hidden">
        <div class="home-hero-bg" aria-hidden="true"></div>
        <div class="container position-relative py-lg-4">
            <div class="row align-items-center hero-row gy-4">
                <div class="col-lg-6" data-aos="fade-right">
                    <p class="home-kicker text-uppercase small fw-semibold mb-2">Comunidade criativa</p>
                    <h1 class="home-hero-title text-nome fw-bold mb-3">
                        Conectando artistas e solicitantes em um só lugar
                    </h1>
                    <p class="lead text-muted mb-4">
                        Cadastre seu portfólio, receba pedidos de orçamento com formulários personalizados e fortaleça sua reputação com avaliações — tudo no MeuPortfólio.
                    </p>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        @guest
                            <button type="button" class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#cadastroModal">
                                Cadastrar-se
                            </button>
                            <a href="{{ route('login') }}" class="btn btn-outline-custom">Entrar</a>
                        @else
                            <a href="{{ route('usuarios.perfilPublico', Auth::id()) }}" class="btn btn-primary-custom">Meu perfil</a>
                            @if(Auth::user()->tipo_usuario == 2)
                                <a href="{{ route('perfil') }}" class="btn btn-outline-custom">Editar portfólio</a>
                            @endif
                        @endguest
                        <a href="{{ route('usuarios.publico') }}" class="btn btn-outline-custom">
                            <i class="bi bi-search me-1"></i> Buscar portfólios
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center" data-aos="fade-left">
                    <div class="home-hero-visual mx-auto">
                        <div class="home-float-icon home-float-1"><i class="bi bi-palette-fill"></i></div>
                        <div class="home-float-icon home-float-2"><i class="bi bi-camera-fill"></i></div>
                        <div class="home-float-icon home-float-3"><i class="bi bi-chat-heart-fill"></i></div>
                        <div class="home-float-card shadow-lg">
                            <i class="bi bi-stars text-purple display-4"></i>
                            <p class="mt-3 mb-0 fw-semibold text-purple">Orçamentos organizados</p>
                            <p class="small text-muted mb-0">Formulários sob medida para cada artista</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- BENEFÍCIOS --}}
    <section class="py-5 bg-white">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="text-nome h3 fw-bold">Por que usar o MeuPortfólio?</h2>
                <p class="text-muted mx-auto mb-0" style="max-width: 560px;">Ferramentas pensadas para quem cria e para quem contrata serviços culturais.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="card custom-card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="home-icon-wrap mb-3"><i class="bi bi-folder2-open"></i></div>
                            <h3 class="h5 fw-semibold text-purple">Portfólios organizados</h3>
                            <p class="text-muted small mb-0">Posts e categorias para mostrar seu trabalho com clareza.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card custom-card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="home-icon-wrap mb-3"><i class="bi bi-ui-checks-grid"></i></div>
                            <h3 class="h5 fw-semibold text-purple">Orçamentos sob medida</h3>
                            <p class="text-muted small mb-0">Formulários personalizados por artista para receber pedidos estruturados.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card custom-card border-0 shadow-sm h-100">
                        <div class="card-body p-4">
                            <div class="home-icon-wrap mb-3"><i class="bi bi-star-half"></i></div>
                            <h3 class="h5 fw-semibold text-purple">Feedback e credibilidade</h3>
                            <p class="text-muted small mb-0">Avaliações para construir confiança entre artistas e solicitantes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PARA QUEM É --}}
    <section class="py-5 home-section-muted">
        <div class="container">
            <div class="text-center mb-4" data-aos="fade-up">
                <h2 class="text-nome h3 fw-bold">Para quem é a plataforma?</h2>
                <p class="text-muted mb-0">Escolha um perfil para ver os principais benefícios.</p>
            </div>

            <div class="d-flex justify-content-center mb-4" data-aos="fade-up">
                <div class="btn-group home-toggle-group shadow-sm" role="group" aria-label="Tipo de usuário">
                    <button type="button" class="btn btn-primary-custom px-4 active" style="margin-right: 16px" id="btnArtista" aria-pressed="true">Sou artista</button>
                    <button type="button" class="btn btn-outline-custom px-4" id="btnContratante" aria-pressed="false">Sou solicitante</button>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div id="conteudoArtista" class="home-toggle-panel card border-0 shadow-sm" data-aos="zoom-in">
                        <div class="card-body p-4 p-md-5">
                            <ul class="list-unstyled mb-4 home-benefit-list">
                                <li><i class="bi bi-check-circle-fill text-purple me-2"></i> Portfólio público com posts e categorias</li>
                                <li><i class="bi bi-check-circle-fill text-purple me-2"></i> Formulário de orçamento configurável</li>
                                <li><i class="bi bi-check-circle-fill text-purple me-2"></i> Notificações quando receber pedidos</li>
                                <li><i class="bi bi-check-circle-fill text-purple me-2"></i> Timeline e avaliações após trabalhos</li>
                            </ul>
                            @guest
                                <a href="{{ route('usuarios.createArtista') }}" class="btn btn-primary-custom btn-sm">Cadastro artista</a>
                            @endguest
                        </div>
                    </div>

                    <div id="conteudoContratante" class="home-toggle-panel card border-0 shadow-sm d-none" data-aos="zoom-in">
                        <div class="card-body p-4 p-md-5">
                            <ul class="list-unstyled mb-4 home-benefit-list">
                                <li><i class="bi bi-check-circle-fill text-purple me-2"></i> Buscar portfólios por área</li>
                                <li><i class="bi bi-check-circle-fill text-purple me-2"></i> Enviar orçamento com ou sem cadastro</li>
                                <li><i class="bi bi-check-circle-fill text-purple me-2"></i> Acompanhar propostas quando estiver logado</li>
                                <li><i class="bi bi-check-circle-fill text-purple me-2"></i> Avaliar artistas após serviços</li>
                            </ul>
                            @guest
                                <a href="{{ route('usuarios.createContratante') }}" class="btn btn-primary-custom btn-sm">Cadastro solicitante</a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- DEPOIMENTOS --}}
    <section class="py-5 bg-white">
        <div class="container text-center">
            <h2 class="text-nome h3 fw-bold mb-4" data-aos="fade-up">O que dizem por aí</h2>

            <div id="carouselDepoimentos" class="carousel slide carousel-dark mx-auto home-carousel" data-bs-ride="carousel" data-bs-interval="6000" data-aos="fade-up">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselDepoimentos" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Depoimento 1"></button>
                    <button type="button" data-bs-target="#carouselDepoimentos" data-bs-slide-to="1" aria-label="Depoimento 2"></button>
                    <button type="button" data-bs-target="#carouselDepoimentos" data-bs-slide-to="2" aria-label="Depoimento 3"></button>
                </div>
                <div class="carousel-inner rounded-4 shadow-sm bg-light px-4 py-5">
                    <div class="carousel-item active">
                        <blockquote class="mb-2 fst-italic text-muted mx-auto" style="max-width: 520px;">“Organizei meu portfólio e passei a receber pedidos com informações bem mais claras.”</blockquote>
                        <div class="text-warning"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                        <footer class="small text-muted mt-2">Artista visual</footer>
                    </div>
                    <div class="carousel-item">
                        <blockquote class="mb-2 fst-italic text-muted mx-auto" style="max-width: 520px;">“Facilitou encontrar profissionais e comparar trabalhos antes de fechar.”</blockquote>
                        <div class="text-warning"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                        <footer class="small text-muted mt-2">Solicitante</footer>
                    </div>
                    <div class="carousel-item">
                        <blockquote class="mb-2 fst-italic text-muted mx-auto" style="max-width: 520px;">“O formulário de orçamento reduziu idas e vindas por mensagem.”</blockquote>
                        <div class="text-warning"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i></div>
                        <footer class="small text-muted mt-2">Músico</footer>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselDepoimentos" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon rounded-circle bg-dark bg-opacity-25" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselDepoimentos" data-bs-slide="next">
                    <span class="carousel-control-next-icon rounded-circle bg-dark bg-opacity-25" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            </div>
        </div>
    </section>

    {{-- CTA FINAL --}}
    <section class="py-5 home-cta text-center text-white position-relative overflow-hidden">
        <div class="home-cta-bg" aria-hidden="true"></div>
        <div class="container position-relative py-3" data-aos="fade-up">
            <h2 class="h3 fw-bold mb-3">Pronto para começar?</h2>
            <p class="opacity-75 mb-4 mx-auto" style="max-width: 480px;">Explore portfólios ou crie sua conta — artista ou solicitante.</p>
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <a href="{{ route('usuarios.publico') }}" class="btn btn-light text-purple fw-semibold px-4">Buscar portfólios</a>
                @guest
                    <button type="button" class="btn btn-outline-light px-4" data-bs-toggle="modal" data-bs-target="#cadastroModal">Criar conta</button>
                    <a href="{{ route('login') }}" class="btn btn-outline-light px-4">Já tenho conta</a>
                @else
                    <a href="{{ route('usuarios.perfilPublico', Auth::id()) }}" class="btn btn-outline-light px-4">Ir ao meu perfil</a>
                @endguest
                <a href="{{ route('sobrepage') }}" class="btn btn-outline-light px-4">Sobre o projeto</a>
            </div>
        </div>
    </section>

</main>

@include('Components.footer')

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof AOS !== 'undefined') {
        AOS.init({ duration: 750, once: true, offset: 48, easing: 'ease-out-cubic' });
    }

    var btnArtista = document.getElementById('btnArtista');
    var btnContratante = document.getElementById('btnContratante');
    var elArtista = document.getElementById('conteudoArtista');
    var elContratante = document.getElementById('conteudoContratante');

    function setToggle(isArtista) {
        if (!btnArtista || !btnContratante || !elArtista || !elContratante) return;

        btnArtista.classList.toggle('btn-primary-custom', isArtista);
        btnArtista.classList.toggle('btn-outline-custom', !isArtista);
        btnArtista.classList.toggle('active', isArtista);
        btnArtista.setAttribute('aria-pressed', isArtista ? 'true' : 'false');

        btnContratante.classList.toggle('btn-primary-custom', !isArtista);
        btnContratante.classList.toggle('btn-outline-custom', isArtista);
        btnContratante.classList.toggle('active', !isArtista);
        btnContratante.setAttribute('aria-pressed', !isArtista ? 'true' : 'false');

        elArtista.classList.toggle('d-none', !isArtista);
        elContratante.classList.toggle('d-none', isArtista);

        if (typeof AOS !== 'undefined') {
            AOS.refresh();
        }
    }

    btnArtista.addEventListener('click', function () { setToggle(true); });
    btnContratante.addEventListener('click', function () { setToggle(false); });
});
</script>

</body>
</html>
