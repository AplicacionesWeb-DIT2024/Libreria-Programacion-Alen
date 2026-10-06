<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entidad extends Model
{
    use HasFactory;

    protected $table = 'entidades_crediticias';

    public function banco() {
        return $this->belongsTo('App\Models\Banco', 'codigo_banco');
    }
}
