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
        Schema::create('libros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('autor');  //Clave foranea
            $table->integer('stock');
            $table->foreignId('pais_origen');
            $table->foreignId('pais_impresion');
            $table->integer('edicion');
            $table->year('anio_publicacion');
            $table->float('precio');
            $table->foreignId('categoria'); //Clave foranea
            $table->foreignId('subcategoria'); //Clave foranea


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
