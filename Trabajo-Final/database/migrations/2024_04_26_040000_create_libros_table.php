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

            //Autor 2
            $table->unsignedBigInteger('autor2')->nullable();
            $table->foreign('autor2')->references('id')->on('autores');

            //Autor 3
            $table->unsignedBigInteger('autor3')->nullable();
            $table->foreign('autor3')->references('id')->on('autores');

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

            $table->string('imagen_referencia')->nullable();

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
