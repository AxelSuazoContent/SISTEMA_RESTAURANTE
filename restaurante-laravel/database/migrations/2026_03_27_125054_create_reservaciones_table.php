<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mesa_id')->constrained('mesas')->onDelete('cascade');
            $table->string('cliente_nombre')->nullable();
            $table->string('cliente_telefono')->nullable();
            $table->date('fecha');
            $table->time('hora');
            $table->text('notas')->nullable();
            $table->enum('estado', ['pendiente', 'completada', 'cancelada'])->default('pendiente');
            $table->foreignId('usuario_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservaciones');
    }
};