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
            $table->string('idioma');
            $table->unsignedBigInteger('autor');
            $table->foreign('autor')->references('id')->on('autores');
            $table->integer('stock');

            $table->unsignedBigInteger('autor2')->nullable();
            $table->foreign('autor2')->references('id')->on('autores');

            $table->unsignedBigInteger('autor3')->nullable();
            $table->foreign('autor3')->references('id')->on('autores');

            $table->string('pais_origen');
            $table->string('pais_impresion');

            $table->integer('edicion');
            $table->year('anio_publicacion');
            $table->float('precio');

            $table->unsignedBigInteger('categoria');
            $table->foreign('categoria')->references('id')->on('categorias');

            $table->unsignedBigInteger('subcategoria');
            $table->foreign('subcategoria')->references('id')->on('subcategorias');

            $table->unsignedBigInteger('editorial');
            $table->foreign('editorial')->references('id')->on('editoriales');

            $table->string('imagen_original');
            $table->string('imagen_original_public_id');
            $table->string('imagen_referencia_2')->nullable();
            $table->string('imagen_referencia_2_public_id')->nullable();
            $table->string('imagen_referencia_3')->nullable();
            $table->string('imagen_referencia_3_public_id')->nullable();

            $table->timestamps();
            $table->string('usuario_creacion');
            $table->string('usuario_modificacion');
            $table->boolean('activo');
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
