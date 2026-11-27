<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\EstadoFactura;

class FacturaEstado extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

     protected $fillable = [
        'factura_id',
        'estado',
        'user_id',
    ];

    protected function casts(): array{
        return [
            'estado' => EstadoFactura::class,
        ];
    }

    public function factura(){
        return $this->belongsTo(Factura::class, 'factura_id');
    }
}
