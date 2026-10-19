<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\EstadoFactura;


class Factura extends Model
{
    use HasFactory;



    public function cerrarComo(EstadoFactura $nuevoEstado): bool{

        return DB::transaction(function () use ($nuevoEstado) {
            $cambiadas = static::where('id', $this->id)
                ->where('estado', EstadoFactura::Pendiente)
                ->update(['estado' => $nuevoEstado]);

            if ($cambiadas === 0) {
                return false; 
            }

            if ($nuevoEstado->devuelveStock()) {
                foreach (DetalleFactura::where('factura', $this->id)->get() as $detalle) {
                    Libro::where('id', $detalle->libro)->increment('stock', $detalle->cantidad);
                }
            }

            return true;
        });
    }


    protected function casts(): array{
        return [
            'estado' => EstadoFactura::class,
        ];
    }


    public function detalles() {
        return $this->hasMany('App\Models\DetalleFactura', 'factura');
    }

    protected $fillable = [
        'cliente',
        'fecha',
        'hora',
        'precioTotal',
        'cantidadTotal',
        'estado',
        'domicilio_calle', 
        'domicilio_numero', 
        'domicilio_localidad',
        'domicilio_provincia', 
        'domicilio_codigo_postal',
    ];
}
