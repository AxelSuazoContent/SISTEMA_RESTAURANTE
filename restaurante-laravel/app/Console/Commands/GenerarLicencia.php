<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

class GenerarLicencia extends Command
{
    protected $signature   = 'licencia:generar {años : Años de licencia (5, 10 o 20)}';
    protected $description = 'Genera una clave de licencia para el sistema';

    public function handle()
    {
        $años = (int) $this->argument('años');

        if (!in_array($años, [5, 10, 20])) {
            $this->error('Solo se permiten licencias de 5, 10 o 20 años.');
            return;
        }

        $expiracion = Carbon::now()->addYears($años)->format('Y-m-d');
        $tipo       = match($años) {
            5  => 'BASIC',
            10 => 'PRO',
            20 => 'PREMIUM',
        };

        $datos = $tipo . '|' . $expiracion . '|' . env('APP_KEY');
        $hash  = strtoupper(substr(hash('sha256', $datos), 0, 16));
        $clave = $tipo . '-' . $expiracion . '-' . substr($hash, 0, 8) . '-' . substr($hash, 8, 8);

        $this->info('✅ Licencia generada correctamente');
        $this->table(
            ['Campo', 'Valor'],
            [
                ['Tipo',       $tipo],
                ['Expira',     $expiracion],
                ['Clave',      $clave],
            ]
        );
        $this->warn('⚠️  Guarda esta clave, no se puede recuperar.');
    }
}