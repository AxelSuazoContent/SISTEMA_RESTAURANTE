<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BackupController extends Controller
{
    public function index()
    {
        $carpeta  = storage_path('app/backups');
        $archivos = [];

        if (file_exists($carpeta)) {
            $lista = glob($carpeta . '/backup_*.sqlite');
            rsort($lista);
            foreach ($lista as $archivo) {
                $archivos[] = [
                    'nombre' => basename($archivo),
                    'tamaño' => round(filesize($archivo) / 1024, 2) . ' KB',
                    'fecha'  => Carbon::createFromTimestamp(filemtime($archivo))->format('d/m/Y H:i:s'),
                ];
            }
        }

        return view('admin.backups.index', compact('archivos'));
    }

    public function crear()
    {
        \Artisan::call('backup:database');
        return back()->with('success', 'Backup creado correctamente.');
    }

    public function descargar($nombre)
    {
        $ruta = storage_path('app/backups/' . $nombre);

        if (!file_exists($ruta)) {
            return back()->with('error', 'Backup no encontrado.');
        }

        return response()->download($ruta);
    }

    public function restaurar(Request $request)
    {
        $request->validate([
            'backup' => 'required|file',
        ]);

        $archivo = $request->file('backup');
        $destino = database_path('database.sqlite');

        // Hacer backup antes de restaurar
        \Artisan::call('backup:database');

        copy($archivo->getRealPath(), $destino);

        return back()->with('success', 'Base de datos restaurada correctamente.');
    }

    public function eliminar($nombre)
    {
        $ruta = storage_path('app/backups/' . $nombre);

        if (file_exists($ruta)) {
            unlink($ruta);
        }

        return back()->with('success', 'Backup eliminado correctamente.');
    }
}