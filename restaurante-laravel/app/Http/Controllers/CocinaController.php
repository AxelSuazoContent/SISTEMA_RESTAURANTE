<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use Illuminate\Http\Request;

class CocinaController extends Controller
{
    /**
     * Vista principal de cocina
     */
    public function index()
    {
        // Pedidos pendientes con sus detalles
        $pedidosPendientes = Pedido::with(['detalles' => function ($query) {
                $query->whereIn('estado', ['pendiente', 'preparando'])
                    ->with('producto');
            }, 'mesa'])
            ->whereIn('estado', ['pendiente', 'preparando'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Detalles pendientes agrupados por pedido
        $detallesPendientes = DetallePedido::with(['pedido.mesa', 'producto'])
            ->whereIn('estado', ['pendiente', 'preparando'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->groupBy('pedido_id');

        // Pedidos listos para entregar
        $pedidosListos = Pedido::with(['detalles.producto', 'mesa'])
            ->where('estado', 'listo')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        // Estadísticas
        $stats = [
            'pendientes' => Pedido::where('estado', 'pendiente')->count(),
            'preparando' => Pedido::where('estado', 'preparando')->count(),
            'listos' => Pedido::where('estado', 'listo')->count(),
            'items_pendientes' => DetallePedido::where('estado', 'pendiente')->count(),
        ];

        return view('cocina.index', compact(
            'pedidosPendientes',
            'detallesPendientes',
            'pedidosListos',
            'stats'
        ));
    }

    /**
     * Obtener pedidos pendientes (para actualización en tiempo real)
     */
    public function pedidosPendientes()
    {
        $pedidos = Pedido::with(['detalles' => function ($query) {
                $query->whereIn('estado', ['pendiente', 'preparando'])
                    ->with('producto');
            }, 'mesa'])
            ->whereIn('estado', ['pendiente', 'preparando'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'pedidos' => $pedidos,
            'stats' => [
                'pendientes' => Pedido::where('estado', 'pendiente')->count(),
                'preparando' => Pedido::where('estado', 'preparando')->count(),
                'listos' => Pedido::where('estado', 'listo')->count(),
                'items_pendientes' => DetallePedido::where('estado', 'pendiente')->count(),
            ],
        ]);
    }

    /**
     * Iniciar preparación de un detalle
     */
    public function iniciarPreparacion(DetallePedido $detalle)
    {
        if ($detalle->estado !== 'pendiente') {
            return response()->json([
                'success' => false,
                'message' => 'Este item no está pendiente.',
            ], 422);
        }

        $detalle->estado = 'preparando';
        $detalle->save();

        // Actualizar estado del pedido si es necesario
        $pedido = $detalle->pedido;
        if ($pedido->estado === 'pendiente') {
            $pedido->estado = 'preparando';
            $pedido->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Preparación iniciada.',
        ]);
    }

    /**
     * Marcar detalle como listo
     */
    public function marcarListo(DetallePedido $detalle)
    {
        if (!in_array($detalle->estado, ['pendiente', 'preparando'])) {
            return response()->json([
                'success' => false,
                'message' => 'Este item no puede marcarse como listo.',
            ], 422);
        }

        $detalle->estado = 'listo';
        $detalle->save();

        // Verificar si todos los detalles del pedido están listos
        $pedido = $detalle->pedido;
        $pendientes = $pedido->detalles()
            ->whereIn('estado', ['pendiente', 'preparando'])
            ->count();

        if ($pendientes === 0) {
            $pedido->estado = 'listo';
            $pedido->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Item marcado como listo.',
            'pedido_completo' => $pendientes === 0,
        ]);
    }

    /**
     * Marcar todo un pedido como listo
     */
    public function marcarPedidoListo(Pedido $pedido)
    {
        if (!in_array($pedido->estado, ['pendiente', 'preparando'])) {
            return response()->json([
                'success' => false,
                'message' => 'Este pedido no puede marcarse como listo.',
            ], 422);
        }

        // Marcar todos los detalles como listos
        $pedido->detalles()
            ->whereIn('estado', ['pendiente', 'preparando'])
            ->update(['estado' => 'listo']);

        $pedido->estado = 'listo';
        $pedido->save();

        return response()->json([
            'success' => true,
            'message' => 'Pedido completo marcado como listo.',
        ]);
    }

    /**
     * Marcar detalle como entregado
     */
    public function marcarEntregado(DetallePedido $detalle)
    {
        if ($detalle->estado !== 'listo') {
            return response()->json([
                'success' => false,
                'message' => 'Este item debe estar listo primero.',
            ], 422);
        }

        $detalle->estado = 'entregado';
        $detalle->save();

        return response()->json([
            'success' => true,
            'message' => 'Item marcado como entregado.',
        ]);
    }

    /**
     * Ver detalle de un pedido
     */
    public function verPedido(Pedido $pedido)
    {
        return response()->json([
            'pedido' => $pedido->load(['detalles.producto', 'mesa']),
        ]);
    }

    /**
     * Obtener sonido de notificación (polling)
     */
    public function checkNuevosPedidos(Request $request)
    {
        $ultimoCheck = $request->input('ultimo_check', now()->subMinutes(5));
        
        $nuevosPedidos = Pedido::where('created_at', '>', $ultimoCheck)
            ->where('estado', 'pendiente')
            ->count();

        return response()->json([
            'nuevos' => $nuevosPedidos > 0,
            'cantidad' => $nuevosPedidos,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}
