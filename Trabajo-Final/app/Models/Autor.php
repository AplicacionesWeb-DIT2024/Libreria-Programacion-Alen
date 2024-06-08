<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;

    protected $table = 'autores';

    public function pais() {
        return $this->belongsTo('App\Models\Pais', 'pais_origen');
    }

    public function libros() {
        return $this->hasMany('App\Models\Libro');
    }
}
