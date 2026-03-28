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
        return view('landing.reservaciones', compact('config'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'telefono'  => 'required|string|max:20',
            'email'     => 'nullable|email|max:255',
            'fecha'     => 'required|date|after_or_equal:today',
            'hora'      => 'required|string',
            'personas'  => 'required|integer|min:1',
            'ocasion'   => 'nullable|string|max:255',
            'notas'     => 'nullable|string|max:1000',
        ]);

        // Buscar cualquier mesa disponible — se asignará después por el admin
        $mesa = Mesa::where('estado', 'disponible')->first();

        Reservacion::create([
            'mesa_id'          => $mesa?->id ?? Mesa::first()->id,
            'cliente_nombre'   => $request->nombre,
            'cliente_telefono' => $request->telefono,
            'fecha'            => $request->fecha,
            'hora'             => date('H:i', strtotime($request->hora)),
            'notas'            => trim(($request->ocasion ? "Ocasión: {$request->ocasion}\n" : '') . ($request->notas ?? '')),
            'estado'           => 'pendiente',
            'usuario_id'       => 1, // sistema
        ]);

        return response()->json(['success' => true]);
    }
}