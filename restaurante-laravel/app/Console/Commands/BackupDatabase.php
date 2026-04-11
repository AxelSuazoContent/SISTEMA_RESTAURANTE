<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BackupDatabase extends Command
{
    protected $signature   = 'backup:database';
    protected $description = 'Hace un backup automático de la base de datos SQLite';

    public function handle()
    {
        $origen  = database_path('database.sqlite');
        $carpeta = storage_path('app/backups');

        if (!file_exists($carpeta)) {
            mkdir($carpeta, 0755, true);
        }

        $nombre  = 'backup_' . Carbon::now()->format('Y-m-d_H-i-s') . '.sqlite';
        $destino = $carpeta . '/' . $nombre;

        copy($origen, $destino);

        // Conservar solo los últimos 7 backups
        $archivos = glob($carpeta . '/backup_*.sqlite');
        usort($archivos, fn($a, $b) => filemtime($a) - filemtime($b));

        while (count($archivos) > 7) {
            unlink(array_shift($archivos));
        }

        $this->info("Backup creado: $nombre");
    }
}