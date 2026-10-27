<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    use HasFactory;

    public function pais() {
        return $this->belongsTo('App\Models\Pais', 'pais_origen');
    }

    public function entidades() {
        return $this->hasMany('App\Models\Entidades');
    }

}
