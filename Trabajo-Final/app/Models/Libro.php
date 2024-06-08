<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    public function autor_origen() {
        return $this->belongsTo('App\Models\Autor', 'autor');
    }

    public function autor_secundario() {
        return $this->belongsTo('App\Models\Autor', 'autor2');
    }

    public function autor_secundario2() {
        return $this->belongsTo('App\Models\Autor', 'autor3');
    }


    public function editorial_perteneciente() {
        return $this->belongsTo('App\Models\Editorial', 'editorial');
    }
    
    public function categoria_perteneciente() {
        return $this->belongsTo('App\Models\Categoria', 'categoria');
    }

    public function subcategoria_perteneciente() {
        return $this->belongsTo('App\Models\Subcategoria', 'subcategoria');
    }

    public function pais_de_origen() {
        return $this->belongsTo('App\Models\Pais', 'pais_origen');
    }

    public function pais_de_impresion() {
        return $this->belongsTo('App\Models\Pais', 'pais_impresion');
    }

    public function getAutores() {
        $autores = [];

        if ($this->autor_origen) {
            $autores[] = $this->autor_origen->apellido . ' ' . $this->autor_origen->nombre;
        }
        
        if ($this->autor_secundario) {
            $autores[] = $this->autor_secundario->apellido . ' ' . $this->autor_secundario->nombre;
        }

        if ($this->autor_secundario2) {
            $autores[] = $this->autor_secundario2->apellido . ' ' . $this->autor_secundario2->nombre;
        }

        return implode(', ', $autores);
    }
    

}
