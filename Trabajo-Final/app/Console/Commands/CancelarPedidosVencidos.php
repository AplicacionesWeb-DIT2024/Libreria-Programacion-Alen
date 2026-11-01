<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Enums\EstadoFactura;
use App\Models\Factura;

class CancelarPedidosVencidos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancelar-pedidos-vencidos';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancela los pedidos pendientes que superaron el tiempo de espera y devuelve el stock';


    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $limite = now()->subMinutes(config('pedidos.minutos_vencimiento'));
        $canceladas = 0;

        Factura::where('estado', EstadoFactura::Pendiente)
            ->where('created_at', '<=', $limite)
            ->chunkById(100, function ($facturas) use (&$canceladas) {
                foreach ($facturas as $factura) {
                    if ($factura->cerrarComo(EstadoFactura::Cancelada)) {
                        $canceladas++;
                    }
                }
            });

        $this->info("Pedidos cancelados: {$canceladas}");

        return self::SUCCESS;
    }
}
