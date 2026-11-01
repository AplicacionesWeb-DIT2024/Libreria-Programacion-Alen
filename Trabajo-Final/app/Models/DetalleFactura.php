<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Libro;
use App\Models\Factura;

class DetalleFactura extends Model
{
    use HasFactory;

    protected $fillable = [
        'libro',
        'factura',
        'precio_unitario',
        'cantidad',
        'precio_total',
    ];

    public function datosLibro() {
        return $this->belongsTo(Libro::class, 'libro');
    }

    public function facturaPadre()
    {
        return $this->belongsTo(Factura::class, 'factura');
    }
}
