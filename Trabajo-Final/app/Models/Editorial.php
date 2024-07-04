<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
