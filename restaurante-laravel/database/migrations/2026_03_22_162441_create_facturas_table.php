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
        $table->string('numero_factura')->unique(); // FAC-2026-00001
        $table->foreignId('pedido_id')->constrained('pedidos');
        $table->foreignId('pago_id')->constrained('pagos');
        $table->foreignId('usuario_id')->constrained('users');
        $table->decimal('subtotal', 10, 2);
        $table->decimal('impuesto', 10, 2)->default(0);
        $table->decimal('total', 10, 2);
        $table->string('metodo_pago');
        $table->string('cliente_nombre')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('facturas');
}
};
