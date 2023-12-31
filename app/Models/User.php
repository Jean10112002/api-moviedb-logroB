<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps=false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'categoria',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function Seguidos(): HasMany
    {
        return $this->hasMany(Seguidor::class,'user_seguidor_id');
    }
    public function Seguidores(): HasMany
    {
        return $this->hasMany(Seguidor::class,'user_seguido_id');
    }
    public function Favoritos(): HasMany
    {
        return $this->hasMany(Favorito::class);
    }
    public function Comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class);
    }
    public function Calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class);
    }

}
