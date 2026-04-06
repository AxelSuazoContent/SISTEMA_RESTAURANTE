<?php

namespace App\Http\Controllers;

use App\Models\ConfigFactura;
use App\Models\Mesa;
use App\Models\Reservacion;
use Illuminate\Http\Request;

class LandingController extends Controller
{
 public function index()
{
    $config = ConfigFactura::obtener();

    $apertura = (int) explode(':', env('HORARIO_APERTURA', '11:00'))[0];
    $cierre   = (int) explode(':', env('HORARIO_CIERRE', '22:00'))[0];

    $horas = [];
    for ($h = $apertura; $h < $cierre; $h++) {
        $horas[] = sprintf('%02d:00', $h);
        $horas[] = sprintf('%02d:30', $h);
    }

    return view('landing.reservaciones', compact('config', 'horas'));
}

public function store(Request $request)
{
    $request->validate([
        'nombre'   => 'required|string|max:255',
        'telefono' => 'required|string|max:20',
        'email'    => 'nullable|email|max:255',
        'fecha'    => 'required|date|after_or_equal:today',
        'hora'     => 'required|string',
        'personas' => 'required|integer|min:1',
        'ocasion'  => 'nullable|string|max:255',
        'notas'    => 'nullable|string|max:1000',
    ]);

    // Buscar mesa disponible
    $mesa = Mesa::where('estado', 'disponible')->first()
            ?? Mesa::first();

    if (!$mesa) {
        return response()->json([
            'success' => false,
            'message' => 'No hay mesas disponibles en este momento.'
        ], 422);
    }

    // Usar el primer admin como usuario del sistema
    $usuarioSistema = \App\Models\User::where('rol', 'admin')->first();

    if (!$usuarioSistema) {
        return response()->json([
            'success' => false,
            'message' => 'Error de configuración del sistema.'
        ], 500);
    }

    Reservacion::create([
        'mesa_id'          => $mesa->id,
        'cliente_nombre'   => $request->nombre,
        'cliente_telefono' => $request->telefono,
        'fecha'            => $request->fecha,
        'hora'             => date('H:i', strtotime($request->hora)),
        'notas'            => trim(($request->ocasion ? "Ocasión: {$request->ocasion}\n" : '') . ($request->notas ?? '')),
        'estado'           => 'pendiente',
        'usuario_id'       => $usuarioSistema->id,
    ]);

    return response()->json(['success' => true]);
}

    
}
