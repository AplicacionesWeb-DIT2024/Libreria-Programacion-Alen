<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\EstadoFactura;
use Illuminate\Support\Facades\DB;

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
            $this->registrarEstado($nuevoEstado);

            if ($nuevoEstado->devuelveStock()) {
                foreach (DetalleFactura::where('factura', $this->id)->get() as $detalle) {
                    Libro::where('id', $detalle->libro)->increment('stock', $detalle->cantidad);
                }
            }

            return true;
        });
    }


     public function registrarEstado(EstadoFactura $estado, ?int $usuarioId = null): void{
        FacturaEstado::create([
            'factura_id' => $this->id,
            'estado' => $estado,
            'user_id' => $usuarioId,
        ]);
    }


    protected function casts(): array{
        return [
            'estado' => EstadoFactura::class,
            'fecha' => 'date',
        ];
    }

    public function usuarioCliente()
    {
        return $this->belongsTo(User::class, 'cliente');
    }


    public function detalles() {
        return $this->hasMany('App\Models\DetalleFactura', 'factura');
    }

    public function historial(){
        return $this->hasMany(FacturaEstado::class, 'factura_id')->orderBy('created_at');
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
        'mp_init_point'
    ];

    public function getDomicilioCompletoAttribute(): string
    {
        return "{$this->domicilio_calle} {$this->domicilio_numero}, {$this->domicilio_localidad}, {$this->domicilio_provincia} - ({$this->domicilio_codigo_postal})";
    }
}
