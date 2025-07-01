<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;



class Usuario extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens , HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome', 
        'documento', 
        'email', 
        'sexo_usuario', 
        'senha',
        'tipo_usuario', 
        'data_nasc', 
        'cep', 
        'bairro',
        'endereco', 
        'cidade',
        'telefone'
    ];

    protected $hidden = [
        'senha', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'data_nasc' => 'date',
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoUsuario::class, 'tipo_usuario');
    }


 //   public function sexo()
   // {
  //      return $this->belongsTo(SexoUsuario::class, 'sexo_usuario');
  //  }

    
    public function getAuthPassword()
    {
        return $this->senha;
    }

    public function getIdadeAttribute()
{
    return Carbon::parse($this->data_nasc)->age;
}

public function portfolioArtista()
{
    return $this->hasOne(PortfolioArtista::class, 'id_usuario');
}

public function portfolio()
{
    return $this->hasOne(\App\Models\PortfolioArtista::class, 'id_usuario');
}


public function categoriasArtisticas()
{
    return $this->belongsToMany(CategoriaArtistica::class, 'categorias_usuarios', 'id_usuario', 'id_categoria');
}


 public function todosFeedbacksRecebidosArtista()
    {
        return $this->hasManyThrough(
            FeedbackArtista::class,     
            PortfolioArtista::class,    
            'id_usuario',               
            'id_artista',               
            'id',                        
            'id'                       
        );
    }
    
   public function todosFeedbacksRecebidosContratante()
    {
       
        return $this->hasMany(FeedbackContratante::class, 'id_usuario');
    }

public function notificacoes()
{
    return $this->hasMany(Notificacao::class);
}
}