<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'apertura' => 'required|date_format:H:i',
            'cierre'   => 'required|date_format:H:i|after:apertura',
        ]);

        Configuracion::set('HORARIO_APERTURA', $request->apertura);
        Configuracion::set('HORARIO_CIERRE', $request->cierre);

        return back()->with('success', 'Horario actualizado correctamente.');
    }
}