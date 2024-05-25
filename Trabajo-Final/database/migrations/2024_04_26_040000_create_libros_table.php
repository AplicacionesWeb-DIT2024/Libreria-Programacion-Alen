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

            //Autor
            $table->unsignedBigInteger('autor');
            $table->foreign('autor')->references('id')->on('autores');
            $table->integer('stock');

            //Pais de origen y de impresion
            $table->unsignedBigInteger('pais_origen');
            $table->unsignedBigInteger('pais_impresion');

            $table->foreign('pais_origen')->references('id')->on('paises');
            $table->foreign('pais_impresion')->references('id')->on('paises');

            $table->integer('edicion');
            $table->year('anio_publicacion');
            $table->float('precio');

            //Categoria
            $table->unsignedBigInteger('categoria');
            $table->foreign('categoria')->references('id')->on('categorias');

            //Subcategoria
            $table->unsignedBigInteger('subcategoria');
            $table->foreign('subcategoria')->references('id')->on('subcategorias');

            //Editorial
            $table->unsignedBigInteger('editorial');
            $table->foreign('editorial')->references('id')->on('editoriales');

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
