<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seguidor extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $fillable=["user_seguidor_id","user_seguido_id"];
    public function UsuarioSeguido(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_seguido_id');
    }
    public function UsuarioSeguidor(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_seguidor_id');
    }
}
