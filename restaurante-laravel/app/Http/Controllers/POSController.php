<?php

namespace App\Http\Controllers;
use App\Models\Factura;
use App\Models\Categoria;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Reservacion;


class POSController extends Controller
{
    /**
     * Vista principal del POS
     */
    public function index()
{
    $mesas = Mesa::orderBy('numero')->get();
    $categorias = Categoria::where('activo', true)
        ->with(['productos' => function ($query) {
            $query->where('activo', true)->orderBy('nombre');
        }])
        ->orderBy('orden')
        ->get();

    $pedidosActivos = Pedido::with(['mesa', 'detalles.producto'])
        ->whereIn('estado', ['pendiente', 'preparando', 'listo'])
        ->orderBy('created_at', 'desc')
        ->get();

    // true si todas las mesas están inactivas (operaciones cerradas)
   // true si todas las mesas están inactivas
$cajaAbierta = \App\Models\AperturaCaja::cajaAbiertaHoy();
$cierreCaja  = !$cajaAbierta || (Mesa::count() > 0 && Mesa::where('estado', '!=', 'inactiva')->count() === 0);


$reservaciones = Reservacion::vigentes()
    ->orderBy('fecha')
    ->orderBy('hora')
    ->get()
    ->groupBy('mesa_id');

return view('pos.index', compact(
    'mesas', 'categorias', 'pedidosActivos', 'cierreCaja', 'reservaciones'
));
}

    /**
     * Obtener productos por categoría (AJAX)
     */
    public function productosPorCategoria(Categoria $categoria)
    {
        $productos = Producto::where('categoria_id', $categoria->id)
            ->where('activo', true)
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'precio', 'stock', 'imagen']);

        return response()->json($productos);
    }

    /**
     * Crear nuevo pedido
     */
    public function crearPedido(Request $request)
{
    $request->validate([
        'tipo'                  => 'required|in:mesa,llevar,domicilio',
        'mesa_id'              => 'required_if:tipo,mesa|nullable|exists:mesas,id',
        'cliente_nombre'       => 'nullable|string|max:255',
        'cliente_telefono'     => 'nullable|string|max:20',
        'productos'            => 'required|array|min:1',
        'productos.*.id'       => 'required|exists:productos,id',
        'productos.*.cantidad' => 'required|integer|min:1',
        'productos.*.notas'    => 'nullable|string',
        'notas'                => 'nullable|string',
    ]);

    if (!\App\Models\AperturaCaja::cajaAbiertaHoy()) {
        return response()->json([
            'success' => false,
            'message' => 'No se pueden crear pedidos. La caja no ha sido abierta hoy.',
        ], 422);
    }

    DB::beginTransaction();

    try {
        $pedido = Pedido::create([
            'mesa_id'          => $request->mesa_id,
            'usuario_id'       => Auth::id(),
            'cliente_nombre'   => $request->cliente_nombre,
            'cliente_telefono' => $request->cliente_telefono,
            'estado'           => 'pendiente',
            'tipo'             => $request->tipo,
            'notas'            => $request->notas,
        ]);

        foreach ($request->productos as $item) {
            $producto = Producto::find($item['id']);
            $pedido->detalles()->create([
                'producto_id'     => $item['id'],
                'cantidad'        => $item['cantidad'],
                'precio_unitario' => $producto->precio,
                'notas'           => $item['notas'] ?? null,
                'estado'          => 'pendiente',
            ]);
            $producto->stock -= $item['cantidad'];
            $producto->save();
        }

        $pedido->actualizarTotal();

        if ($request->mesa_id) {
            $mesa = Mesa::find($request->mesa_id);
            $mesa->estado = 'ocupada';
            $mesa->save();
        }
        

        DB::commit();

        return response()->json([
            'success' => true,
            'pedido'  => $pedido->load(['detalles.producto', 'mesa']),
            'message' => 'Pedido creado correctamente.',
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error al crear el pedido: ' . $e->getMessage(),
        ], 500);
    }
}
    /**
     * Agregar productos a un pedido existente
     */
    public function agregarProductos(Request $request, Pedido $pedido)
    {
        if (!$pedido->puedeEditarse()) {
            return response()->json([
                'success' => false,
                'message' => 'No se pueden agregar productos a este pedido.',
            ], 422);
        }

        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.notas' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            foreach ($request->productos as $item) {
                $producto = Producto::find($item['id']);
                
                // Verificar si ya existe el producto en el pedido
                $detalleExistente = $pedido->detalles()
                    ->where('producto_id', $item['id'])
                    ->where('estado', 'pendiente')
                    ->first();

                if ($detalleExistente && empty($item['notas'])) {
                    // Actualizar cantidad
                    $detalleExistente->cantidad += $item['cantidad'];
                    $detalleExistente->save();
                } else {
                    // Crear nuevo detalle
                    $pedido->detalles()->create([
                        'producto_id' => $item['id'],
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $producto->precio,
                        'notas' => $item['notas'] ?? null,
                        'estado' => 'pendiente',
                    ]);
                }

                // Descontar stock
                $producto->stock -= $item['cantidad'];
                $producto->save();
            }

            // Recalcular total
            $pedido->actualizarTotal();

            DB::commit();

            return response()->json([
                'success' => true,
                'pedido' => $pedido->load(['detalles.producto', 'mesa']),
                'message' => 'Productos agregados correctamente.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar productos: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ver detalle de un pedido
     */
    public function verPedido(Pedido $pedido)
    {
        return response()->json([
            'pedido' => $pedido->load(['detalles.producto.categoria', 'mesa', 'usuario', 'pago']),
        ]);
    }

    /**
     * Cancelar pedido
     */
    public function cancelarPedido(Request $request, Pedido $pedido)
    {
        if (!$pedido->puedeCancelarse()) {
            return response()->json([
                'success' => false,
                'message' => 'Este pedido no puede ser cancelado.',
            ], 422);
        }

       $request->validate([
    'motivo' => 'nullable|string',
]);


        DB::beginTransaction();

        try {
            // Devolver stock
            foreach ($pedido->detalles as $detalle) {
                $producto = $detalle->producto;
                $producto->stock += $detalle->cantidad;
                $producto->save();
            }

            // Actualizar pedido
            $pedido->estado = 'cancelado';
            $pedido->notas = ($pedido->notas ? $pedido->notas . "\n" : '') . 
                'Cancelado: ' . $request->motivo;
            $pedido->save();

            // Liberar mesa
            if ($pedido->mesa) {
                $pedido->mesa->estado = 'disponible';
                $pedido->mesa->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pedido cancelado correctamente.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar el pedido: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Procesar pago de un pedido
     */
    public function procesarPago(Request $request, Pedido $pedido)
    {
        if ($pedido->estado === 'pagado') {
            return response()->json([
                'success' => false,
                'message' => 'Este pedido ya ha sido pagado.',
            ], 422);
        }

        $request->validate([
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia,otro',
            'monto_recibido' => 'required|numeric|min:' . $pedido->total,
            'referencia' => 'nullable|string|max:255',
        ]);

        if ($request->metodo_pago === 'transferencia' && empty($request->referencia)) {
    return response()->json([
        'success' => false,
        'message' => 'El código de transacción es obligatorio para transferencias.',
    ], 422);
}

        DB::beginTransaction();

        try {
            $cambio = $request->monto_recibido - $pedido->total;

            // Crear el pago
            Pago::create([
            'pedido_id'   => $pedido->id,
            'metodo_pago' => $request->metodo_pago,
            'monto'       => $pedido->total,
            'cambio'      => $cambio,
            'referencia'  => $request->referencia,
            'notas'       => $request->notas,
            'cliente_rtn' => $request->cliente_rtn,
        ]);

            // Actualizar pedido
            $pedido->estado = 'pagado';
            $pedido->fecha_completado = now();
            $pedido->save();

            // Liberar mesa
            if ($pedido->mesa) {
                $pedido->mesa->estado = 'disponible';
                $pedido->mesa->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'cambio' => $cambio,
                'message' => 'Pago procesado correctamente.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pago: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cambiar estado de un pedido a entregado
     */
    public function marcarEntregado(Pedido $pedido)
    {
        if ($pedido->estado !== 'listo') {
            return response()->json([
                'success' => false,
                'message' => 'El pedido debe estar listo para marcarlo como entregado.',
            ], 422);
        }

        $pedido->estado = 'entregado';
        $pedido->save();

        return response()->json([
            'success' => true,
            'message' => 'Pedido marcado como entregado.',
        ]);
    }

    /**
     * Liberar mesa manualmente
     */
    public function liberarMesa(Mesa $mesa)
    {
        // Verificar si tiene pedidos activos
        $pedidoActivo = Pedido::where('mesa_id', $mesa->id)
            ->whereIn('estado', ['pendiente', 'preparando', 'listo'])
            ->first();

        if ($pedidoActivo) {
            return response()->json([
                'success' => false,
                'message' => 'La mesa tiene un pedido activo.',
            ], 422);
        }

        $mesa->estado = 'disponible';
        $mesa->save();

        return response()->json([
            'success' => true,
            'message' => 'Mesa liberada correctamente.',
        ]);
    }
    /**
 * Ocupar mesa manualmente
 */
public function ocuparMesa(Mesa $mesa)
{
    $mesa->estado = 'ocupada';
    $mesa->save();

    return response()->json([
        'success' => true,
        'message' => 'Mesa ocupada correctamente.',
    ]);
}

public function mesasCambiarEstado(Request $request, Mesa $mesa)
{
    $request->validate([
        'estado'       => 'required|in:disponible,ocupada,reservada,inactiva',
        'hora_reserva' => 'required_if:estado,reservada|nullable|date_format:H:i',
    ]);

    $mesa->estado       = $request->estado;
    $mesa->hora_reserva = $request->estado === 'reservada' ? $request->hora_reserva : null;
    $mesa->save();

    return back()->with('success', 'Estado de la mesa actualizado.');
}

public function generarFactura(Request $request, Pedido $pedido)
{
    // Verificar que el pedido esté pagado
    if ($pedido->estado !== 'pagado') {
        return response()->json(['success' => false, 'message' => 'El pedido no está pagado.']);
    }

    // Si ya tiene factura, retornarla
    $facturaExistente = Factura::where('pedido_id', $pedido->id)->first();
    if ($facturaExistente) {
        return response()->json([
            'success'      => true,
            'factura_id'   => $facturaExistente->id,
            'numero'       => $facturaExistente->numero_factura,
            'imprimir_url' => route('admin.facturas.imprimir', $facturaExistente),
        ]);
    }

    $pago = $pedido->pago;

    $factura = Factura::create([
        'numero_factura' => Factura::generarNumero(),
        'pedido_id'      => $pedido->id,
        'pago_id'        => $pago->id,
        'usuario_id'     => auth()->id(),
        'subtotal'       => $pedido->total,
        'impuesto'       => 0,
        'total'          => $pedido->total,
        'metodo_pago'    => $pago->metodo_pago,
        'cliente_nombre' => $pedido->mesa ? 'Mesa '.$pedido->mesa->numero : $pedido->cliente_nombre,
    ]);

    return response()->json([
        'success'      => true,
        'factura_id'   => $factura->id,
        'numero'       => $factura->numero_factura,
        'imprimir_url' => route('admin.facturas.imprimir', $factura),
    ]);
}

public function pedidoActivoPorMesa(Mesa $mesa)
{
    $pedido = Pedido::with(['detalles.producto', 'mesa', 'usuario', 'pago'])
        ->where('mesa_id', $mesa->id)
        ->whereIn('estado', ['pendiente', 'preparando', 'listo', 'entregado'])
        ->latest()
        ->first();

    if (!$pedido) {
        return response()->json(['success' => false, 'message' => 'No hay pedido activo.']);
    }

    return response()->json(['success' => true, 'pedido' => $pedido]);
}
}
