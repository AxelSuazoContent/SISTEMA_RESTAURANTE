<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VerificarLicencia
{
    public function handle(Request $request, Closure $next)
    {
        // Rutas que no necesitan licencia
        if ($request->is('licencia') || $request->is('licencia/*') || $request->routeIs('login') || $request->routeIs('logout')) {
            return $next($request);
        }

        $clave = $this->obtenerClave();

        if (!$clave || !$this->esValida($clave)) {
            return redirect()->route('licencia.index');
        }

        return $next($request);
    }

    private function obtenerClave(): ?string
    {
        $ruta = storage_path('app/licencia.key');
        if (!file_exists($ruta)) return null;
        return trim(file_get_contents($ruta));
    }

    public static function esValida(string $clave): bool
    {
        // Formato: TIPO-FECHA-HASH
        // Ejemplo: BASIC-2029-12-31-ABCD1234
        $partes = explode('-', $clave);
        if (count($partes) !== 6) return false;

        $tipo       = $partes[0];
        $expiracion = $partes[1] . '-' . $partes[2] . '-' . $partes[3];
        $hashDado   = $partes[4] . '-' . $partes[5];

        if (!in_array($tipo, ['BASIC', 'PRO', 'PREMIUM', 'DEMO'])) return false;

        // Verificar que no haya expirado
        try {
            $fechaExp = Carbon::parse($expiracion);
            if (Carbon::now()->startOfDay()->greaterThan($fechaExp->endOfDay())) return false;
        } catch (\Exception $e) {
            return false;
        }

        // Verificar hash
        $datos    = $tipo . '|' . $expiracion . '|' . env('APP_KEY');
        $hash     = strtoupper(substr(hash('sha256', $datos), 0, 16));
        $esperada = $tipo . '-' . $expiracion . '-' . substr($hash, 0, 8) . '-' . substr($hash, 8, 8);

        return $clave === $esperada;
    }

    public static function obtenerInfo(): ?array
    {
        $ruta = storage_path('app/licencia.key');
        if (!file_exists($ruta)) return null;

        $clave  = trim(file_get_contents($ruta));
        $partes = explode('-', $clave);
        if (count($partes) !== 6) return null;

        $tipo       = $partes[0];
        $expiracion = $partes[1] . '-' . $partes[2] . '-' . $partes[3];

        return [
            'tipo'       => $tipo,
            'expiracion' => Carbon::parse($expiracion)->format('d/m/Y'),
            'dias'       => Carbon::now()->diffInDays(Carbon::parse($expiracion)),
            'valida'     => self::esValida($clave),
        ];
    }
}