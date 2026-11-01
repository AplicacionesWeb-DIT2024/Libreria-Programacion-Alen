<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;

    public function libros() {
        return $this->hasMany('App\Models\Libro');
    }

     public function categoria_perteneciente() {
        return $this->belongsTo('App\Models\Categoria', 'categoria');
    }

     public function usuarioCreacion()
    {
        return $this->belongsTo(User::class, 'usuario_creacion');
    }

    public function usuarioModificacion()
    {
        return $this->belongsTo(User::class, 'usuario_modificacion');
    }
}
