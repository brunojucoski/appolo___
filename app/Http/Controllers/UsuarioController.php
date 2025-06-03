<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\TipoUsuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\CategoriaArtistica;




class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = \App\Models\Usuario::with('tipo')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = TipoUsuario::all();
        return view('usuarios.create', compact('tipos'));
    }

    public function createArtista() {
        return view('usuarios.cadastro_artista');
    }
    
    public function createContratante() {
        return view('usuarios.cadastro_contratante');
    }
    

    public function storeArtista(Request $request)
    {
        return $this->storeWithTipo($request, 2); // 2 = artista
    }
    
    public function storeContratante(Request $request)
    {
        return $this->storeWithTipo($request, 3); // 3 = contratante
    }
    
    private function storeWithTipo(Request $request, $tipoUsuario)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:200',
            'documento' => 'required|string|unique:usuarios',
            'email' => 'required|email|unique:usuarios',
            'senha' => 'required|min:6',
            'data_nasc' => 'required|date',
            'sexo_usuario' => 'required|integer',
        ]);
    
        $usuario = new Usuario();
        $usuario->fill($request->except('senha'));
        $usuario->senha = Hash::make($request->senha);
        $usuario->tipo_usuario = $tipoUsuario; 
        $usuario->save();
    
        $token = $usuario->createToken('token')->plainTextToken;
    
        return redirect()->route('login')->with('success', 'Usuário criado com sucesso!');
    }
    



    public function perfil()
    {
        $usuario = Auth::user();
        $usuario->load('portfolioArtista.posts.imagens', 'categoriasArtisticas');
        
        $posts = $usuario->portfolioArtista->posts ?? collect();
        $categorias = CategoriaArtistica::all();
        $categoriasSelecionadas = $usuario->categoriasArtisticas->pluck('id')->toArray();
    
        return view('usuarios.perfil_publico', compact('usuario', 'categorias', 'categoriasSelecionadas'));
    }



    
    public function showPerfilPublico($id)
    {
        
        $usuario = Usuario::with('portfolioArtista.posts.imagens', 'categoriasArtisticas')->findOrFail($id);
        $posts = $usuario->portfolioArtista->posts ?? collect();
    
        $portfolio = $usuario->portfolioArtista; 

        $categorias = CategoriaArtistica::all();
        $categoriasSelecionadas = $usuario->categoriasArtisticas->pluck('id')->toArray();

        

            return view('usuarios.perfil_publico', compact('usuario', 'posts', 'categorias', 'categoriasSelecionadas', 'portfolio'));
    
    }


    public function showshowPublic($id)
    {
        $usuario = Usuario::with('portfolioArtista.posts.imagens', 'categoriasArtisticas')->findOrFail($id);
        $posts = $usuario->portfolioArtista->posts ?? collect();
    
        $categorias = CategoriaArtistica::all();
        $categoriasSelecionadas = $usuario->categoriasArtisticas->pluck('id')->toArray();
    
        return view('usuarios.perfil_publico', compact('usuario', 'posts', 'categorias', 'categoriasSelecionadas'));
    }





    public function editInterno()
{
    $usuario = Auth::user();
    $tiposUsuario = TipoUsuario::all();

    return view('usuarios.edit_interno', compact('usuario', 'tiposUsuario'));
}







    public function edit($id)
{
    $usuario = Usuario::findOrFail($id);
    $tiposUsuario = TipoUsuario::all();

    return view('usuarios.edit_interno', compact('usuario', 'tiposUsuario'));
}
    /**
     * Update the specified resource in storage.
     */






    public function update(Request $request, $id)
{
    $usuario = Usuario::findOrFail($id);

    $request->validate([
        'nome' => 'required|string|max:255',
        'telefone' => 'nullable|string|max:20',
        'cidade' => 'nullable|string|max:255',
        'cep' => 'nullable|string|max:20',
        'bairro' => 'nullable|string|max:255',
        'endereco' => 'nullable|string|max:255',
        'senha' => 'nullable|string|min:8|confirmed',
        'foto_perfil' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ]);

    $usuario->nome = $request->nome;
    $usuario->telefone = $request->telefone;
    $usuario->cidade = $request->cidade;
    $usuario->cep = $request->cep;
    $usuario->bairro = $request->bairro;
    $usuario->endereco = $request->endereco;

    if ($request->filled('senha')) {
        $usuario->senha = Hash::make($request->senha);
    }

    if ($request->hasFile('foto_perfil')) {
    
        if ($usuario->foto_perfil) {
            Storage::delete($usuario->foto_perfil);
        }

        $path = $request->file('foto_perfil')->store('fotos_perfil', 'public');
        $usuario->foto_perfil = $path;
    }

    $usuario->save();

    return redirect()->back()->with('success', 'Perfil atualizado com sucesso!');
}
    


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

//listar artistas no site 
    
public function listarPublico(Request $request)
{
    $query = Usuario::where('tipo_usuario', 2)
        ->with(['categoriasArtisticas'])
        ->whereNotNull('nome');

    if ($request->filled('categoria')) {
        $categoriaId = $request->categoria;
        $query->whereHas('categoriasArtisticas', function ($q) use ($categoriaId) {
            $q->where('categorias_usuarios.id_categoria', $categoriaId);
        });
    }

    if ($request->filled('cidade')) {
        $query->where('cidade', 'like', '%' . $request->cidade . '%');
    }

    $usuarios = $query->get();
    $categorias = CategoriaArtistica::all();

    $cidades = Usuario::whereNotNull('cidade')
    ->where('tipo_usuario', 2)
    ->distinct()
    ->pluck('cidade');

    return view('artistas', compact('usuarios', 'categorias', 'cidades'));
}

    
//listar contratantes no site 

public function listarContratantes(Request $request)
{
    $usuarios = Usuario::where('tipo_usuario', 3)
        ->when($request->cidade, function ($query) use ($request) {
            $query->where('cidade', $request->cidade);
        })
        ->get();

    $cidades = Usuario::where('tipo_usuario', 3)
        ->whereNotNull('cidade')
        ->pluck('cidade')
        ->unique()
        ->sort()
        ->values();

    return view('contratantes', compact('usuarios', 'cidades'));
}

    //editar e add foto de perfil 
    
    
}
