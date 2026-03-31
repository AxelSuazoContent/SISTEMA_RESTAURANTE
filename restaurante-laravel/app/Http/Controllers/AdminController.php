<?php

namespace App\Http\Controllers;

use App\Models\AperturaCaja;
use App\Models\ConfigFactura;
use App\Models\Factura;
use App\Models\Categoria;
use App\Models\Mesa;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Reservacion;


class AdminController extends Controller
{
    /**
     * Dashboard del administrador
     */
    public function dashboard()
    {
        // Estadísticas del día
        $hoy = Carbon::today();
        $ventasHoy = Pedido::whereDate('created_at', $hoy)
            ->where('estado', 'pagado')
            ->sum('total');
        
        $pedidosHoy = Pedido::whereDate('created_at', $hoy)->count();
        
        $pedidosActivos = Pedido::whereIn('estado', ['pendiente', 'preparando', 'listo'])->count();
        
        // Productos con bajo stock
        $productosBajoStock = Producto::where('stock', '<', 10)
            ->where('activo', true)
            ->count();

        // Pedidos recientes
        $pedidosRecientes = Pedido::with(['mesa', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Ventas de la semana
        $inicioSemana = Carbon::now()->startOfWeek();
        $ventasSemana = Pedido::where('created_at', '>=', $inicioSemana)
            ->where('estado', 'pagado')
            ->selectRaw('DATE(created_at) as fecha, SUM(total) as total')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        // Productos más vendidos
        $productosTop = \DB::table('detalles_pedido')
            ->join('productos', 'detalles_pedido.producto_id', '=', 'productos.id')
            ->select('productos.nombre', \DB::raw('SUM(detalles_pedido.cantidad) as total_vendido'))
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('total_vendido')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'ventasHoy',
            'pedidosHoy',
            'pedidosActivos',
            'productosBajoStock',
            'pedidosRecientes',
            'ventasSemana',
            'productosTop'
        ));
    }

    // ==================== USUARIOS ====================

    /**
     * Lista de usuarios
     */
    public function usuariosIndex()
    {
        $usuarios = User::orderBy('nombre')->paginate(20);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    /**
     * Formulario para crear usuario
     */
    public function usuariosCreate()
    {
        return view('admin.usuarios.create');
    }

    /**
     * Guardar nuevo usuario
     */
    public function usuariosStore(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'rol' => 'required|in:admin,recepcionista,cocina',
            'telefono' => 'nullable|digits:8',
        ]);

        User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'telefono' => $request->telefono,
            'activo' => $request->has('activo') ? 1 : 0,
        ]);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Formulario para editar usuario
     */
    public function usuariosEdit(User $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    /**
     * Actualizar usuario
     */
    public function usuariosUpdate(Request $request, User $usuario)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuario->id,
            'rol' => 'required|in:admin,recepcionista,cocina',
            'telefono' => 'nullable|string|max:20',
        ]);

        $data = [
            'nombre' => $request->nombre,
            'email' => $request->email,
            'rol' => $request->rol,
            'telefono' => $request->telefono,
            'activo' => $request->has('activo') ? 1 : 0,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $usuario->update($data);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Eliminar usuario
     */
    public function usuariosDestroy(User $usuario)
    {
        // No permitir eliminar el último administrador
        if ($usuario->rol === 'admin' && User::where('rol', 'admin')->count() <= 1) {
            return back()->with('error', 'No puedes eliminar el último administrador.');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }

    // ==================== CATEGORÍAS ====================

    /**
     * Lista de categorías
     */
    public function categoriasIndex()
    {
        $categorias = Categoria::orderBy('orden')->paginate(20);
        return view('admin.categorias.index', compact('categorias'));
    }

    /**
     * Guardar nueva categoría
     */
    public function categoriasStore(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'color' => 'required|string|max:7',
            'orden' => 'nullable|integer',
            'orden'  => 'required|integer|min:0|unique:categorias,orden,' . $categoria->id,
        ]);

        Categoria::create([
    'nombre'      => $request->nombre,
    'descripcion' => $request->descripcion,
    'color'       => $request->color,
    'orden'       => $request->orden,
    'activo'      => $request->has('activo') ? 1 : 0,
]);

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Actualizar categoría
     */
    public function categoriasUpdate(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'color' => 'required|string|max:7',
            'orden' => 'nullable|integer',
            'activo' => 'boolean',
        ]);

        $categoria->update([
    'nombre'      => $request->nombre,
    'descripcion' => $request->descripcion,
    'color'       => $request->color,
    'orden'       => $request->orden,
    'activo'      => $request->has('activo') ? 1 : 0,
]);

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Eliminar categoría
     */
    public function categoriasDestroy(Categoria $categoria)
    {
        // Verificar si tiene productos
        if ($categoria->productos()->count() > 0) {
            return back()->with('error', 'No puedes eliminar una categoría con productos.');
        }

        $categoria->delete();

        return redirect()->route('admin.categorias.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }

    // ==================== PRODUCTOS ====================

    /**
     * Lista de productos
     */
    public function productosIndex()
    {
        $productos = Producto::with('categoria')
            ->orderBy('nombre')
            ->paginate(20);
        
        $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();
        
        return view('admin.productos.index', compact('productos', 'categorias'));
    }

    /**
     * Formulario para crear producto
     */
    public function productosCreate()
    {
        $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();
        return view('admin.productos.create', compact('categorias'));
    }

    /**
     * Guardar nuevo producto
     */
    public function productosStore(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'costo' => 'nullable|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'stock' => 'nullable|integer|min:0',
            'preparacion_minutos' => 'nullable|integer|min:1',
            'imagen' => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['imagen']);
    $data['activo'] = $request->has('activo') ? 1 : 0;

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Producto::create($data);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    /**
     * Formulario para editar producto
     */
    public function productosEdit(Producto $producto)
    {
        $categorias = Categoria::where('activo', true)->orderBy('nombre')->get();
        return view('admin.productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Actualizar producto
     */
    public function productosUpdate(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'costo' => 'nullable|numeric|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'stock' => 'nullable|integer|min:0',
            'preparacion_minutos' => 'nullable|integer|min:1',
            'imagen' => 'nullable|image|max:2048',
            'activo' => 'boolean',
        ]);

        $data = $request->except(['imagen']);
        $data['activo'] = $request->has('activo') ? 1 : 0;

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($producto->imagen) {
                \Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Eliminar producto
     */
    public function productosDestroy(Producto $producto)
    {
        // Eliminar imagen si existe
        if ($producto->imagen) {
            \Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('admin.productos.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    // ==================== MESAS ====================

    /**
     * Lista de mesas
     */
    public function mesasIndex()
    {
        $mesas = Mesa::orderBy('numero')->paginate(20);
        return view('admin.mesas.index', compact('mesas'));
    }

    /**
     * Guardar nueva mesa
     */
    public function mesasStore(Request $request)
    {
        $request->validate([
            'numero' => 'required|integer|unique:mesas',
            'capacidad' => 'required|integer|min:1',
            'ubicacion' => 'nullable|string|max:255',
        ]);

        Mesa::create($request->all());

        return redirect()->route('admin.mesas.index')
            ->with('success', 'Mesa creada correctamente.');
    }

    /**
     * Actualizar mesa
     */
    public function mesasUpdate(Request $request, Mesa $mesa)
    {
        $request->validate([
            'numero' => 'required|integer|unique:mesas,numero,' . $mesa->id,
            'capacidad' => 'required|integer|min:1',
            'ubicacion' => 'nullable|string|max:255',
        ]);

        $mesa->update($request->all());

        return redirect()->route('admin.mesas.index')
            ->with('success', 'Mesa actualizada correctamente.');
    }

    /**
     * Eliminar mesa
     */
    public function mesasDestroy(Mesa $mesa)
    {
        // Verificar si tiene pedidos
        if ($mesa->pedidos()->count() > 0) {
            return back()->with('error', 'No puedes eliminar una mesa con pedidos.');
        }

        $mesa->delete();

        return redirect()->route('admin.mesas.index')
            ->with('success', 'Mesa eliminada correctamente.');
    }
    public function mesasCambiarEstado(Request $request, Mesa $mesa)
{
    // Si viene hora_reserva, es una reservación — no cambiar estado de mesa
    if ($request->filled('hora_reserva')) {
        $request->validate([
            'fecha_reserva'    => 'required|date|after_or_equal:today',
            'hora_reserva'     => 'required|date_format:H:i',
            'cliente_nombre'   => 'nullable|string|max:255',
            'cliente_telefono' => 'nullable|string|max:20',
            'notas'            => 'nullable|string',
        ]);

        Reservacion::create([
            'mesa_id'          => $mesa->id,
            'cliente_nombre'   => $request->cliente_nombre,
            'cliente_telefono' => $request->cliente_telefono,
            'fecha'            => $request->fecha_reserva,
            'hora'             => $request->hora_reserva,
            'notas'            => $request->notas,
            'estado'           => 'pendiente',
            'usuario_id'       => auth()->id(),
        ]);

        return back()->with('success', 'Reservación registrada correctamente.');
    }

    // Cambio normal de estado
    $request->validate([
        'estado' => 'required|in:disponible,ocupada,inactiva',
    ]);

    $mesa->estado = $request->estado;
    $mesa->save();

    return back()->with('success', 'Estado de la mesa actualizado.');
}

// ==================== FACTURAS ====================

public function facturasIndex()
{
    $facturas = Factura::with(['pedido.mesa', 'usuario'])
        ->orderBy('created_at', 'desc')
        ->paginate(20);

    return view('admin.facturas.index', compact('facturas'));
}

public function facturasImprimir(Factura $factura)
{
    $factura->load(['pedido.detalles.producto', 'pedido.mesa', 'usuario', 'pago']);
    return view('admin.facturas.imprimir', compact('factura'));
}

// ==================== CIERRE DE CAJA ====================

// ==================== CAJA ====================

public function cierreCaja(Request $request)
{
    $hoy = Carbon::today();

    $ventas = Pedido::whereDate('created_at', $hoy)
        ->where('estado', 'pagado')
        ->with(['pago', 'mesa'])
        ->get();

    $totalEfectivo      = $ventas->filter(fn($v) => $v->pago?->metodo_pago === 'efectivo')->sum('total');
    $totalTarjeta       = $ventas->filter(fn($v) => $v->pago?->metodo_pago === 'tarjeta')->sum('total');
    $totalTransferencia = $ventas->filter(fn($v) => $v->pago?->metodo_pago === 'transferencia')->sum('total');
    $totalOtro          = $ventas->filter(fn($v) => $v->pago?->metodo_pago === 'otro')->sum('total');
    $totalDia           = $ventas->sum('total');
    $totalPedidos       = $ventas->count();

    // Apertura de caja del día
    $apertura = AperturaCaja::cajaAbiertaHoy();

    // Si hay caja abierta, calcular diferencia esperada
    $totalEsperado = $apertura ? $apertura->monto_inicial + $totalEfectivo : null;

    return view('admin.cierre-caja', compact(
        'ventas',
        'totalEfectivo',
        'totalTarjeta',
        'totalTransferencia',
        'totalOtro',
        'totalDia',
        'totalPedidos',
        'hoy',
        'apertura',
        'totalEsperado'
    ));
}



public function abrirCaja(Request $request)
{
    $request->validate([
        'monto_inicial' => 'required|numeric|min:0',
        'notas'         => 'nullable|string|max:255',
    ]);

    if (AperturaCaja::cajaAbiertaHoy()) {
        return back()->with('error', 'Ya hay una caja abierta hoy.');
    }

    AperturaCaja::create([
        'usuario_id'    => auth()->id(),
        'monto_inicial' => $request->monto_inicial,
        'notas'         => $request->notas,
        'apertura_at'   => now(),
    ]);

    // 👇 Reactivar todas las mesas inactivas al abrir caja
    Mesa::where('estado', 'inactiva')->update(['estado' => 'disponible']);

    return back()->with('success', 'Caja abierta correctamente.');
}



public function cerrarCaja(Request $request)
{
    $request->validate([
        'monto_final' => 'required|numeric|min:0',
    ]);

    $apertura = AperturaCaja::cajaAbiertaHoy();

    if (!$apertura) {
        return back()->with('error', 'No hay una caja abierta hoy.');
    }

    $hoy      = Carbon::today();
    $ventasDia = Pedido::whereDate('created_at', $hoy)
        ->where('estado', 'pagado')
        ->sum('total');

    $totalEfectivo = Pedido::whereDate('created_at', $hoy)
        ->where('estado', 'pagado')
        ->whereHas('pago', fn($q) => $q->where('metodo_pago', 'efectivo'))
        ->sum('total');

    $esperado   = $apertura->monto_inicial + $totalEfectivo;
    $diferencia = $request->monto_final - $esperado;

    $apertura->update([
        'monto_final' => $request->monto_final,
        'ventas_dia'  => $ventasDia,
        'diferencia'  => $diferencia,
        'cierre_at'   => now(),
    ]);

    return back()->with('success', 'Caja cerrada correctamente.');
}
    // ==================== REPORTES ====================

    /**
     * Reporte de ventas
     */
    public function reporteVentas(Request $request)
    {
        $fechaInicio = $request->filled('fecha_inicio')
            ? Carbon::parse($request->fecha_inicio)->startOfDay()
            : Carbon::now()->startOfMonth()->startOfDay();
 
        $fechaFin = $request->filled('fecha_fin')
            ? Carbon::parse($request->fecha_fin)->endOfDay()
            : Carbon::now()->endOfDay();
 
        // Ventas paginadas para la tabla
        $ventas = Pedido::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('estado', 'pagado')
            ->with(['mesa', 'usuario', 'pago'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);
 
        // Totales
        $totalVentas = Pedido::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('estado', 'pagado')->sum('total');
 
        $totalPedidos = Pedido::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('estado', 'pagado')->count();
 
        $ticketPromedio = $totalPedidos > 0 ? $totalVentas / $totalPedidos : 0;
 
        // Ventas por día (para gráfica de línea)
        $ventasPorDia = Pedido::whereBetween('created_at', [$fechaInicio, $fechaFin])
            ->where('estado', 'pagado')
            ->selectRaw('DATE(created_at) as fecha, SUM(total) as total, COUNT(*) as pedidos')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();
 
        // Ventas por método de pago (para gráfica de dona)
        $ventasPorMetodo = Pago::whereHas('pedido', function ($q) use ($fechaInicio, $fechaFin) {
                $q->whereBetween('created_at', [$fechaInicio, $fechaFin])
                  ->where('estado', 'pagado');
            })
            ->selectRaw('metodo_pago, COUNT(*) as total')
            ->groupBy('metodo_pago')
            ->get();
 
        // Top 5 productos más vendidos (para gráfica de barras)
        $topProductos = \DB::table('detalles_pedido')
            ->join('productos', 'detalles_pedido.producto_id', '=', 'productos.id')
            ->join('pedidos', 'detalles_pedido.pedido_id', '=', 'pedidos.id')
            ->whereBetween('pedidos.created_at', [$fechaInicio, $fechaFin])
            ->where('pedidos.estado', 'pagado')
            ->select('productos.nombre', \DB::raw('SUM(detalles_pedido.cantidad) as total_vendido'))
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('total_vendido')
            ->take(5)
            ->get();
 
        return view('admin.reportes.ventas', compact(
            'ventas',
            'totalVentas',
            'totalPedidos',
            'ticketPromedio',
            'fechaInicio',
            'fechaFin',
            'ventasPorDia',
            'ventasPorMetodo',
            'topProductos'
        ));
        


    }
    // ==================== CONFIG FACTURA ====================

public function configFacturaIndex()
{
    $config = ConfigFactura::obtener();
    return view('admin.config-factura', compact('config'));
}

public function configFacturaUpdate(Request $request)
{
    $request->validate([
        'nombre_negocio'       => 'required|string|max:255',
        'rtn'                  => 'required|digits:14',
        'direccion'            => 'required|string|max:255',
        'telefono'             => 'required|digits:8',
        'cai'                  => 'required|string|max:50',
        'rango_desde'          => 'required|string|max:25',
        'rango_hasta'          => 'required|string|max:25',
        'fecha_limite_emision' => 'required|date',
    ]);

    $config = ConfigFactura::obtener();
    $config->update($request->all());

    return back()->with('success', 'Configuración actualizada correctamente.');
}

public function cerrarOperaciones()
{
    Mesa::whereNotIn('estado', ['inactiva'])->update([
        'estado'       => 'inactiva',
        'hora_reserva' => null,
    ]);

    // Guardar en sesión que las operaciones están cerradas
    cache()->put('operaciones_cerradas', true);

    return response()->json(['success' => true]);
}
public function abrirOperaciones()
{
    Mesa::where('estado', 'inactiva')->update(['estado' => 'disponible']);
    return response()->json(['success' => true]);
}

}


