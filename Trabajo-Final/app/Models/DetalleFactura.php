<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleFactura extends Model
{
    use HasFactory;

    public function libros() {
        return $this->hasMany('App\Models\Libro');
    }

    public function factura() {
        return $this->belongsTo('App\Models\Factura');
    }
}
