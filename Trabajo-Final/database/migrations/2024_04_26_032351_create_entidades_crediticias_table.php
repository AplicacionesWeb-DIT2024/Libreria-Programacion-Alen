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
        Schema::create('entidades_crediticias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('tipo', ['Debito', 'Credito']);
            $table->unsignedBigInteger('codigo_banco');
            $table->foreign('codigo_banco')->references('id')->on('bancos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entidades_crediticias');
    }
};
