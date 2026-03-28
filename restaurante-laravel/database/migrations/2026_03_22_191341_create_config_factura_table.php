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
    Schema::create('config_factura', function (Blueprint $table) {
        $table->id();
        $table->string('nombre_negocio')->default('Restaurante Mi Sabor');
        $table->string('rtn')->default('08011999123456');
        $table->string('direccion')->default('Col. Kennedy, Tegucigalpa, Honduras');
        $table->string('telefono')->default('2234-5678');
        $table->string('cai')->default('A1B2C3-D4E5F6-G7H8I9-J0K1L2-M3N4O5-P6');
        $table->string('rango_desde')->default('001-001-01-00000001');
        $table->string('rango_hasta')->default('001-001-01-00099999');
        $table->date('fecha_limite_emision')->default('2026-12-31');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('config_factura');
}
};
