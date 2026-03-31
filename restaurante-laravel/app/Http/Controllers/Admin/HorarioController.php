<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'apertura' => 'required|date_format:H:i',
            'cierre'   => 'required|date_format:H:i|after:apertura',
        ]);

        // Actualiza el .env
        $this->setEnv('HORARIO_APERTURA', $request->apertura);
        $this->setEnv('HORARIO_CIERRE',   $request->cierre);

        // Limpia el cache de config
        \Artisan::call('config:clear');

        return back()->with('success', 'Horario actualizado correctamente.');
    }

    private function setEnv(string $key, string $value): void
    {
        $path    = base_path('.env');
        $content = file_get_contents($path);

        if (str_contains($content, $key . '=')) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
        } else {
            $content .= "\n{$key}={$value}";
        }

        file_put_contents($path, $content);
    }
}