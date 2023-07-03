<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Calificacion extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $fillable=["user_id","pelicula_id",'calificacion'];
    public function Usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
