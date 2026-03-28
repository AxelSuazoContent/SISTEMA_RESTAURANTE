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
        Schema::create('mesas', function (Blueprint $table) {
            $table->id();
            $table->integer('numero')->unique();
            $table->integer('capacidad')->default(4);
            $table->string('ubicacion')->nullable();
            $table->enum('estado', ['disponible', 'ocupada', 'reservada', 'limpieza'])->default('disponible');
            $table->timestamps();
            $table->time('hora_reserva')->nullable();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesas');
    }
};
