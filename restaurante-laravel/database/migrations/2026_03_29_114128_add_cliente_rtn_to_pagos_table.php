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
    Schema::table('pagos', function (Blueprint $table) {
        $table->string('cliente_rtn')->nullable()->after('referencia');
    });
}

public function down(): void
{
    Schema::table('pagos', function (Blueprint $table) {
        $table->dropColumn('cliente_rtn');
    });
}
};
