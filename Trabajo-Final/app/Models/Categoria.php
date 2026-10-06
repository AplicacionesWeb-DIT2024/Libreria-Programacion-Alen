<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Categoria extends Model
{
    use HasFactory;

    public function libros() {
        return $this->hasMany('App\Models\Libro');
    }

    public function subcategorias() {
        return $this->hasMany('App\Models\Subcategoria', 'categoria');
    }

     public function usuarioCreacion()
    {
        return $this->belongsTo(User::class, 'usuario_creacion');
    }

    public function usuarioModificacion()
    {
        return $this->belongsTo(User::class, 'usuario_modificacion');
    }

    public function scopeNombre(Builder $query, $nombre) 
    {
        if ($nombre) {
            $query->where('nombre', 'LIKE', '%' . $nombre . '%');
        }
        return $query;
    }

     public function scopeActivo(Builder $query, $activo) 
    {
        if ($activo !== null && $activo !== '') {
            $query->where('activo', $activo);
        }
        return $query;
    }

}
