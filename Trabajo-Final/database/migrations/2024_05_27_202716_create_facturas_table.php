<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente');
            $table->foreign('cliente')->references('id')->on('users');
            $table->integer('cantidadTotal')->default(0);
            $table->decimal('precioTotal',12,2)->default(0);
            $table->date('fecha');
            $table->time('hora');
            $table->string('estado'); 
            $table->string('domicilio_calle');
            $table->string('domicilio_numero', 20);
            $table->string('domicilio_localidad');
            $table->string('domicilio_provincia');
            $table->string('domicilio_codigo_postal', 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
