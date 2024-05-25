<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editorial extends Model
{
    use HasFactory;

    protected $table = 'editoriales';

    public function pais() {
        return $this->belongsTo('App\Models\Pais');
    }

    public function libros() {
        return $this->hasMany('App\Models\Libro');
    }
}
