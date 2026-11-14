<?php

namespace App\Enums;

enum EstadoFactura: string
{
    case Pendiente = 'Pendiente';
    case Aceptada = 'Aceptada';
    case Rechazada = 'Rechazada';
    case Cancelada = 'Cancelada';
    case EnPreparacion = 'En preparación';
    case EnDistribucion = 'En distribución';
    case RecibidoDomicilio = 'Recibido en domicilio';

    public function label(): string
    {
        return match ($this) {
            self::Pendiente => 'Pendiente de pago',
            self::Aceptada => 'Aceptada',
            self::Rechazada => 'Rechazada',
            self::Cancelada => 'Cancelada',
            self::EnPreparacion => 'En preparación',
            self::EnDistribucion => 'En distribución',
            self::RecibidoDomicilio => 'Recibido en domicilio'
        };
    }


    public function siguiente(): ?self
    {
        return match ($this) {
            self::Aceptada        => self::EnPreparacion,
            self::EnPreparacion   => self::EnDistribucion,
            self::EnDistribucion  => self::RecibidoDomicilio,
            default               => null,
        };
    }


    public function textoAccion(): ?string
    {
        return match ($this) {
            self::Aceptada        => 'Preparar pedido',
            self::EnPreparacion   => 'En distribución',
            self::EnDistribucion  => 'Recibido',
            default               => null,
        };
    }


    public function badgeClass(): string
    {
        return match ($this) {
            self::Pendiente         => 'bg-warning-subtle text-warning',
            self::Aceptada          => 'bg-success-subtle text-success',
            self::EnPreparacion     => 'bg-info-subtle text-info-emphasis',
            self::EnDistribucion    => 'bg-primary-subtle text-primary',
            self::RecibidoDomicilio => 'bg-success text-white',
            self::Rechazada         => 'bg-danger-subtle text-danger',
            self::Cancelada         => 'bg-secondary-subtle text-secondary',
        };
    }


    public static function pagados(): array
    {
        return [
            self::Aceptada->value,
            self::EnPreparacion->value,
            self::EnDistribucion->value,
            self::RecibidoDomicilio->value,
        ];
    }


    public function esFinal(): bool
    {
        return in_array($this, [self::Rechazada, self::Cancelada, self::RecibidoDomicilio], true);
    }

    public function devuelveStock(): bool
    {
        return in_array($this, [self::Rechazada, self::Cancelada], true);
    }
}