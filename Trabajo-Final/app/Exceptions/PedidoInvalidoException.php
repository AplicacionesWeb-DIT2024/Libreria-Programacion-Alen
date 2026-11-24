<?php

namespace App\Exceptions;

use Exception;

class PedidoInvalidoException extends Exception
{
    public function __construct(string $mensaje, public array $datos = [])
    {
        parent::__construct($mensaje);
    }
}
