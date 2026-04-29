<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_moneda_to_config_factura_table.php
public function up(): void
{
    Schema::table('config_factura', function (Blueprint $table) {
        $table->string('moneda')->default('HNL'); // HNL = Lempiras, USD = Dólares
        $table->string('simbolo_moneda')->default('L.');
    });
}

public function down(): void
{
    Schema::table('config_factura', function (Blueprint $table) {
        $table->dropColumn(['moneda', 'simbolo_moneda']);
    });
}
};
