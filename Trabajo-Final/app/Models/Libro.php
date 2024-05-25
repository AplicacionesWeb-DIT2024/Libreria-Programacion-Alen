<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    public function autor() {
        return $this->belongsTo('App\Models\Autor');
    }

    public function editorial() {
        return $this->belongsTo('App\Models\Editorial');
    }
    
    public function categoria() {
        return $this->belongsTo('App\Models\Categoria');
    }

    public function subcategoria() {
        return $this->belongsTo('App\Models\Subcategoria');
    }
    

}
