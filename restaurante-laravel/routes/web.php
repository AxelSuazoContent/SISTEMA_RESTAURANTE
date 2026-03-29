<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CocinaController;
use App\Http\Controllers\POSController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::get('/', function () {
    return redirect()->route('login');
});
// Landing page pública
Route::get('/reservar', [App\Http\Controllers\LandingController::class, 'index'])->name('landing.reservar');
Route::post('/reservar', [App\Http\Controllers\LandingController::class, 'store'])->name('landing.store');
// Autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Cambiar contraseña
Route::get('/cambiar-password', [AuthController::class, 'showChangePassword'])
    ->name('password.change')->middleware('auth');
Route::post('/cambiar-password', [AuthController::class, 'changePassword'])
    ->middleware('auth');

// Dashboard general
Route::get('/dashboard', function () {
    $user = auth()->user();
    return match($user->rol) {
        'admin'         => redirect()->route('admin.dashboard'),
        'recepcionista' => redirect()->route('pos.index'),
        'cocina'        => redirect()->route('cocina.index'),
        default         => redirect()->route('login'),
    };
})->name('dashboard')->middleware('auth');

// ==================== ADMINISTRADOR ====================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {



// Mesas — accesible para admin y recepcionista
Route::get('/mesas', [AdminController::class, 'mesasIndex'])->name('mesas.index');
Route::patch('/mesas/{mesa}/estado', [AdminController::class, 'mesasCambiarEstado'])->name('mesas.estado');

// Solo admin
Route::middleware('can:admin')->group(function () {
    Route::post('/mesas', [AdminController::class, 'mesasStore'])->name('mesas.store');
    Route::put('/mesas/{mesa}', [AdminController::class, 'mesasUpdate'])->name('mesas.update');
    Route::delete('/mesas/{mesa}', [AdminController::class, 'mesasDestroy'])->name('mesas.destroy');
});

Route::post('/caja/abrir', [AdminController::class, 'abrirCaja'])->name('caja.abrir');
Route::post('/caja/cerrar', [AdminController::class, 'cerrarCaja'])->name('caja.cerrar');
Route::post('/mesas/cerrar-operaciones', [AdminController::class, 'cerrarOperaciones'])->name('mesas.cerrar.operaciones');
Route::post('/mesas/abrir-operaciones', [AdminController::class, 'abrirOperaciones'])->name('mesas.abrir.operaciones');   
    // Caja
Route::middleware('can:admin')->group(function () {
    Route::post('/caja/abrir', [AdminController::class, 'abrirCaja'])->name('caja.abrir');
    Route::post('/caja/cerrar', [AdminController::class, 'cerrarCaja'])->name('caja.cerrar');
});

    Route::post('/mesas/cerrar-operaciones', [AdminController::class, 'cerrarOperaciones'])->name('mesas.cerrar.operaciones');

    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard')->middleware('can:admin');
// Factura imprimir — accesible para recepcionista también
Route::get('/facturas/{factura}/imprimir', [AdminController::class, 'facturasImprimir'])
    ->name('facturas.imprimir');

Route::middleware('can:admin')->group(function () {
    // Usuarios
    Route::get('/usuarios', [AdminController::class, 'usuariosIndex'])->name('usuarios.index');
    Route::get('/usuarios/crear', [AdminController::class, 'usuariosCreate'])->name('usuarios.create');
    Route::post('/usuarios', [AdminController::class, 'usuariosStore'])->name('usuarios.store');
    Route::get('/usuarios/{usuario}/editar', [AdminController::class, 'usuariosEdit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [AdminController::class, 'usuariosUpdate'])->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [AdminController::class, 'usuariosDestroy'])->name('usuarios.destroy');

    // Categorías
    Route::get('/categorias', [AdminController::class, 'categoriasIndex'])->name('categorias.index');
    Route::post('/categorias', [AdminController::class, 'categoriasStore'])->name('categorias.store');
    Route::put('/categorias/{categoria}', [AdminController::class, 'categoriasUpdate'])->name('categorias.update');
    Route::delete('/categorias/{categoria}', [AdminController::class, 'categoriasDestroy'])->name('categorias.destroy');

    // Productos
    Route::get('/productos', [AdminController::class, 'productosIndex'])->name('productos.index');
    Route::get('/productos/crear', [AdminController::class, 'productosCreate'])->name('productos.create');
    Route::post('/productos', [AdminController::class, 'productosStore'])->name('productos.store');
    Route::get('/productos/{producto}/editar', [AdminController::class, 'productosEdit'])->name('productos.edit');
    Route::put('/productos/{producto}', [AdminController::class, 'productosUpdate'])->name('productos.update');
    Route::delete('/productos/{producto}', [AdminController::class, 'productosDestroy'])->name('productos.destroy');

    // Reservaciones
    Route::patch('/mesas/{mesa}/reservar', [AdminController::class, 'mesasCambiarEstado'])->name('mesas.reservar');

    // Reportes
    Route::get('/reportes/ventas', [AdminController::class, 'reporteVentas'])->name('reportes.ventas');

    // Facturas — solo índice para admin
    Route::get('/facturas', [AdminController::class, 'facturasIndex'])->name('facturas.index');

    // Cierre de caja
    Route::get('/cierre-caja', [AdminController::class, 'cierreCaja'])->name('cierre.caja');

    // Configuración de factura
    Route::get('/config-factura', [AdminController::class, 'configFacturaIndex'])->name('config.factura');
    Route::post('/config-factura', [AdminController::class, 'configFacturaUpdate'])->name('config.factura.update');
});
});


// ==================== POS ====================
Route::middleware(['auth'])->prefix('pos')->name('pos.')->group(function () {

    Route::get('/', [POSController::class, 'index'])
        ->name('index')->middleware('can:recepcionista');

    Route::get('/productos/categoria/{categoria}', [POSController::class, 'productosPorCategoria'])
        ->name('productos.categoria')->middleware('can:recepcionista');

    Route::middleware('can:recepcionista')->group(function () {
        Route::post('/pedido/crear', [POSController::class, 'crearPedido'])->name('pedido.crear');
        Route::get('/pedido/{pedido}', [POSController::class, 'verPedido'])->name('pedido.ver');
        Route::post('/pedido/{pedido}/agregar', [POSController::class, 'agregarProductos'])->name('pedido.agregar');
        Route::post('/pedido/{pedido}/cancelar', [POSController::class, 'cancelarPedido'])->name('pedido.cancelar');
        Route::post('/pedido/{pedido}/pagar', [POSController::class, 'procesarPago'])->name('pedido.pagar');
        Route::post('/pedido/{pedido}/entregar', [POSController::class, 'marcarEntregado'])->name('pedido.entregar');
        Route::get('/pedido/{pedido}/ticket', [POSController::class, 'imprimirTicket'])->name('pedido.ticket');
        Route::post('/pedido/{pedido}/factura', [POSController::class, 'generarFactura'])->name('pedido.factura');

        Route::post('/mesa/{mesa}/liberar', [POSController::class, 'liberarMesa'])->name('mesa.liberar');
        Route::post('/mesa/{mesa}/ocupar', [POSController::class, 'ocuparMesa'])->name('mesa.ocupar');
        Route::post('/mesa/{mesa}/estado', [POSController::class, 'cambiarEstadoMesa'])->name('mesa.estado');
        Route::get('/mesa/{mesa}/pedido-activo', [POSController::class, 'pedidoActivoPorMesa'])->name('mesa.pedido.activo');
    });
});



// ==================== COCINA ====================
Route::middleware(['auth'])->prefix('cocina')->name('cocina.')->group(function () {

    Route::get('/', [CocinaController::class, 'index'])
        ->name('index')->middleware('can:cocina');

    Route::middleware('can:cocina')->group(function () {
        Route::get('/pedidos-pendientes', [CocinaController::class, 'pedidosPendientes'])->name('pedidos.pendientes');
        Route::get('/pedido/{pedido}', [CocinaController::class, 'verPedido'])->name('pedido.ver');
        Route::post('/detalle/{detalle}/iniciar', [CocinaController::class, 'iniciarPreparacion'])->name('detalle.iniciar');
        Route::post('/detalle/{detalle}/listo', [CocinaController::class, 'marcarListo'])->name('detalle.listo');
        Route::post('/detalle/{detalle}/entregado', [CocinaController::class, 'marcarEntregado'])->name('detalle.entregado');
        Route::post('/pedido/{pedido}/listo', [CocinaController::class, 'marcarPedidoListo'])->name('pedido.listo');
        Route::get('/check-nuevos', [CocinaController::class, 'checkNuevosPedidos'])->name('check.nuevos');
    });
});