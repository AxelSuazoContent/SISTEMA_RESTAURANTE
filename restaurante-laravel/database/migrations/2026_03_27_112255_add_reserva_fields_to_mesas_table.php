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
    Schema::table('mesas', function (Blueprint $table) {
        $table->date('fecha_reserva')->nullable()->after('hora_reserva');
        $table->string('cliente_nombre')->nullable()->after('fecha_reserva');
        $table->string('cliente_telefono')->nullable()->after('cliente_nombre');
        $table->text('notas')->nullable()->after('cliente_telefono');
    });
}

public function down(): void
{
    Schema::table('mesas', function (Blueprint $table) {
        $table->dropColumn(['fecha_reserva','cliente_nombre','cliente_telefono','notas']);
    });
}
};
