<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Editorial extends Model
{
    use HasFactory;

    protected $table = 'editoriales';

    public function pais_origen() {
        return $this->belongsTo('App\Models\Pais', 'pais');
    }

    public function libros() {
        return $this->hasMany('App\Models\Libro');
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

    public function scopePais(Builder $query, $pais) 
    {
        if ($pais) {
            $query->where('pais', 'LIKE', '%' . $pais . '%');
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
