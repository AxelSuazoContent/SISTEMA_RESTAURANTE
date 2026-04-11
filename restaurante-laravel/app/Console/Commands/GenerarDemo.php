<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class GenerarDemo extends Command
{
    protected $signature   = 'licencia:demo';
    protected $description = 'Genera una licencia demo de 7 días';

    public function handle()
    {
        $expiracion = Carbon::now()->addDays(7)->format('Y-m-d');
        $tipo       = 'DEMO';

        $datos = $tipo . '|' . $expiracion . '|' . env('APP_KEY');
        $hash  = strtoupper(substr(hash('sha256', $datos), 0, 16));
        $clave = $tipo . '-' . $expiracion . '-' . substr($hash, 0, 8) . '-' . substr($hash, 8, 8);

        $this->info('✅ Licencia DEMO generada correctamente');
        $this->table(
            ['Campo', 'Valor'],
            [
                ['Tipo',       'DEMO (7 días)'],
                ['Expira',     $expiracion],
                ['Clave',      $clave],
            ]
        );
        $this->warn('⚠️  Esta licencia expira en 7 días.');
    }
}