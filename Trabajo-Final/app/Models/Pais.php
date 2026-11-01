<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    use HasFactory;

    protected $table = 'paises';


    //Relacion uno a muchos
    public function autors() {
        return $this->hasMany('App\Models\Autor');
    }

    //Relacion uno a muchos
    public function bancos() {
        return $this->hasMany('App\Models\Banco');
    }

    //Relacion uno a muchos
    public function editoriales() {
        return $this->hasMany('App\Models\Editorial');
    }

    public function libros() {
        return $this->hasMany('App\Models\Libro');
    }
}
