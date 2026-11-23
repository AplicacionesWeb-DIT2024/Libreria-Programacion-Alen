<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Artisan::command('paises:exportar', function () {
    $paises = collect(config('paises_telefono'))
        ->map(fn ($p, $codigo) => ['codigo' => $codigo, ...$p])
        ->values();

    $ruta = base_path('../frontend-bytebooks/src/utils/paises_telefono.json');

    File::put($ruta, $paises->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");

    $this->info("Exportado: {$ruta}");
})->purpose('Genera el JSON de países para el frontend');


Schedule::command('app:cancelar-pedidos-vencidos')
    ->everyMinute()
    ->withoutOverlapping();


