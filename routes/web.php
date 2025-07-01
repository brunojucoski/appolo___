<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    CategoriaArtisticaController,
    UsuarioController,
    TipoUsuarioController,
    PortfolioArtistaController,
    ProfileController,
    PostPortfolioController
};
use App\Http\Controllers\NotificacaoController;
use App\Http\Controllers\FeedbackArtistaController;
use App\Http\Controllers\FeedbackContratanteController;
use App\Http\Controllers\PropostaContratoController;
use Illuminate\Http\Request;


//rotas admin : 
Route::get('/login_interno', function () {
    return view('login_interno');
});
Route::post('/login_interno', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        if ($user->tipo_usuario == 1) {
            return redirect()->route('categorias-artisticas.index');
        } else {
            Auth::logout();
            return redirect('/login_interno')->withErrors(['acesso' => 'Acesso não autorizado.']);
        }
    }

    return redirect('/login_interno')->withErrors(['login' => 'Credenciais inválidas.']);
})->name('loginInterno');

Route::get('/meu-perfil', [UsuarioController::class, 'editInterno'])->name('usuarios.editInterno');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('categorias-artisticas', CategoriaArtisticaController::class)
        ->parameters(['categorias-artisticas' => 'categoriaArtistica']);
});



// rotas site PUBLICO


Route::get('/login', function () {
    return view('auth/login');
});

Route::get('/home', function () {
    return view('home');
})->name('homepage');

Route::get('/sobre', function () {
    return view('sobre');
})->name('sobrepage');



Route::get('/', function () {
    return view('home');
});


Route::get('/solicitantes', [UsuarioController::class, 'listarContratantes'])->name('usuarios.contratantes');



// ->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::resource('usuarios', UsuarioController::class);

Route::get('/notificacoes', [NotificacaoController::class, 'index'])->middleware('auth');

Route::get('/usuarios/{id}/perfil-publico', [UsuarioController::class, 'showPerfilPublico'])->name('usuarios.perfilPublico');

Route::get('/cadastro/artista', [UsuarioController::class, 'createArtista'])->name('usuarios.createArtista');
Route::post('/cadastro/artista', [UsuarioController::class, 'storeArtista'])->name('usuarios.storeArtista');

Route::get('/cadastro/contratante', [UsuarioController::class, 'createContratante'])->name('usuarios.createContratante');
Route::post('/cadastro/contratante', [UsuarioController::class, 'storeContratante'])->name('usuarios.storeContratante');

Route::post('/usuarios/artista', [UsuarioController::class, 'storeArtista'])->name('usuarios.storeArtista');
Route::post('/usuarios/contratante', [UsuarioController::class, 'storeContratante'])->name('usuarios.storeContratante');



// portfolio 
Route::middleware(['auth'])->group(function () {
    Route::resource('portfolio', PortfolioArtistaController::class)->except(['show']);
});


Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [UsuarioController::class, 'perfil'])->name('perfil');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/posts', [PostPortfolioController::class, 'store'])->name('posts.store');
    Route::put('/posts/{post}', [PostPortfolioController::class, 'update'])->name('posts.update'); 
    Route::delete('/posts/{post}', [PostPortfolioController::class, 'destroy'])->name('posts.destroy'); 
});

//proposta de trampo
Route::post('/propostas', [PropostaContratoController::class, 'store'])
    ->middleware('auth')
    ->name('propostas.store');
    
Route::middleware('auth')->post('/responder-proposta/{id}', [PropostaContratoController::class, 'responder'])
     ->name('proposta.responder');

Route::middleware('auth')->get('/minhas-propostas', [PropostaContratoController::class, 'minhasPropostas'])->name('propostas.minhas');


     
//listagem dos usuários
Route::get('/artistas', [UsuarioController::class, 'listarPublico'])->name('usuarios.publico');

Route::get('/perfil/{id}', [UsuarioController::class, 'showPublic'])->name('usuarios.public');
Route::post('/notificacoes/ler-todas', [NotificacaoController::class, 'lerTodas'])->name('notificacoes.lerTodas');
Route::post('/notificacoes/{id}/marcar-lida', [NotificacaoController::class, 'marcarComoLida']);



//FEEDBACKS

Route::middleware(['auth'])->group(function () {
    // Verificações separadas
    Route::get('/feedbacks/pendentes/artistas', [FeedbackArtistaController::class, 'verificarPendentes'])->name('feedbacks.pendentes.artistas');
    Route::get('/feedbacks/pendentes/contratantes', [FeedbackContratanteController::class, 'verificarPendentes'])->name('feedbacks.pendentes.contratantes');

    // Stores
    Route::post('/feedbacks/artistas', [FeedbackArtistaController::class, 'store'])->name('feedbacks.artistas.store');
    Route::post('/feedbacks/contratantes', [FeedbackContratanteController::class, 'store'])->name('feedbacks.contratantes.store');
});
//LOGOUT
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

require __DIR__.'/auth.php';
