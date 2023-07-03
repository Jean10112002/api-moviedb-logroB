<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorito extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $fillable=["pelicula_id","user_id"];
    public function Usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
