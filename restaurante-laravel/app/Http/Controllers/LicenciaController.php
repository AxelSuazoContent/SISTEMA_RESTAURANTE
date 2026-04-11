<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\VerificarLicencia;

class LicenciaController extends Controller
{
    public function index()
    {
        $info = VerificarLicencia::obtenerInfo();
        return view('licencia.index', compact('info'));
    }

    public function activar(Request $request)
    {
        $request->validate([
            'clave' => 'required|string',
        ]);

        $clave = strtoupper(trim($request->clave));

        if (!VerificarLicencia::esValida($clave)) {
            return back()->with('error', 'La clave de licencia es inválida o ha expirado.');
        }

        file_put_contents(storage_path('app/licencia.key'), $clave);

        return redirect()->route('dashboard')->with('success', '¡Sistema activado correctamente!');
    }
}