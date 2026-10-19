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
        Schema::create('subcategorias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categoria');
            $table->foreign('categoria')->references('id')->on('categorias');

            $table->string('nombre');
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
        Schema::dropIfExists('subcategorias');
    }
};
