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
    Schema::create('aperturas_caja', function (Blueprint $table) {
        $table->id();
        $table->foreignId('usuario_id')->constrained('users');
        $table->decimal('monto_inicial', 10, 2)->default(0);
        $table->decimal('monto_final', 10, 2)->nullable();
        $table->decimal('ventas_dia', 10, 2)->nullable();
        $table->decimal('diferencia', 10, 2)->nullable();
        $table->timestamp('apertura_at');
        $table->timestamp('cierre_at')->nullable();
        $table->string('notas')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('aperturas_caja');
}
};
