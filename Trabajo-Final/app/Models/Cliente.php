<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fecha_nacimiento',
        'telefono',
        'domicilio_calle',
        'domicilio_numero',
        'domicilio_provincia',
        'domicilio_codigo_postal',
        'domicilio_localidad'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date:Y-m-d',
    ];

     public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDomicilioCompletoAttribute(): string
    {
        return "{$this->domicilio_calle} {$this->domicilio_numero}, {$this->domicilio_localidad}, {$this->domicilio_provincia} - ({$this->domicilio_codigo_postal})";
    }
}
