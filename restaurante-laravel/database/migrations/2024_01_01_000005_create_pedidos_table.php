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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mesa_id')->nullable()->constrained('mesas')->onDelete('set null');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->string('cliente_nombre')->nullable();
            $table->string('cliente_telefono')->nullable();
            $table->enum('estado', ['pendiente', 'preparando', 'listo', 'entregado', 'cancelado', 'pagado'])->default('pendiente');
            $table->enum('tipo', ['mesa', 'llevar', 'domicilio'])->default('mesa');
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('propina', 10, 2)->default(0);
            $table->text('notas')->nullable();
            $table->timestamp('fecha_completado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
