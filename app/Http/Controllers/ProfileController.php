<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\CategoriaArtistica;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    
    
    public function showPerfil()
    {
        $usuario = Auth::user();
    
        // Carrega os posts do usuário com imagens e comentários
        $posts = $usuario->posts()->with(['imagens', 'comentarios.usuario'])->get();
    
        return view('perfil.artista', compact('usuario', 'posts'));
    }


    public function show($id)
{
    $usuario = Usuario::with('portfolioArtista', 'categoriasArtisticas')->findOrFail($id);
    $categorias = CategoriaArtistica::all();
    $categoriasSelecionadas = $usuario->categoriasArtisticas->pluck('id')->toArray();

    return view('usuarios.perfil_publico', compact('usuario', 'categorias', 'categoriasSelecionadas'));
}

}
