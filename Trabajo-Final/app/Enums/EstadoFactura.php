<?php

namespace App\Enums;

enum EstadoFactura: string
{
    case Pendiente = 'Pendiente';
    case Aceptada = 'Aceptada';
    case Rechazada = 'Rechazada';
    case Cancelada = 'Cancelada';

    public function label(): string
    {
        return match ($this) {
            self::Pendiente => 'Pendiente de pago',
            self::Aceptada => 'Aceptada',
            self::Rechazada => 'Rechazada',
            self::Cancelada => 'Cancelada',
        };
    }

    public function esFinal(): bool
    {
        return $this !== self::Pendiente;
    }

    public function devuelveStock(): bool
    {
        return in_array($this, [self::Rechazada, self::Cancelada], true);
    }
}