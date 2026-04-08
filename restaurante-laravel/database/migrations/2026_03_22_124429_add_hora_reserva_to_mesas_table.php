<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('mesas', 'hora_reserva')) {
            Schema::table('mesas', function (Blueprint $table) {
                $table->time('hora_reserva')->nullable()->after('estado');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('mesas', 'hora_reserva')) {
            Schema::table('mesas', function (Blueprint $table) {
                $table->dropColumn('hora_reserva');
            });
        }
    }
};