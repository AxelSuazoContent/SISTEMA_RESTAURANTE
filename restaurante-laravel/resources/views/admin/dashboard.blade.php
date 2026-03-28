@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-speedometer2"></i> Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <span class="text-muted">{{ now()->format('d/m/Y H:i') }}</span>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card bg-primary-soft">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="number">${{ number_format($ventasHoy, 2) }}</div>
                    <div class="label">Ventas Hoy</div>
                </div>
                <i class="bi bi-cash-stack"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card bg-success-soft">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="number">{{ $pedidosHoy }}</div>
                    <div class="label">Pedidos Hoy</div>
                </div>
                <i class="bi bi-receipt"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card bg-warning-soft">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="number">{{ $pedidosActivos }}</div>
                    <div class="label">Pedidos Activos</div>
                </div>
                <i class="bi bi-clock-history"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card bg-danger-soft">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="number">{{ $productosBajoStock }}</div>
                    <div class="label">Bajo Stock</div>
                </div>
                <i class="bi bi-exclamation-triangle"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Pedidos Recientes -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt"></i> Pedidos Recientes</span>
                <a href="{{ route('pos.index') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus"></i> Nuevo Pedido
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mesa/Cliente</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Tiempo</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pedidosRecientes as $pedido)
                            <tr>
                                <td>#{{ $pedido->id }}</td>
                                <td>
                                    @if($pedido->mesa)
                                        Mesa {{ $pedido->mesa->numero }}
                                    @else
                                        {{ $pedido->cliente_nombre ?: 'Sin nombre' }}
                                    @endif
                                </td>
                                <td>${{ number_format($pedido->total, 2) }}</td>
                                <td>
                                    <span class="badge-estado estado-{{ $pedido->estado }}">
                                        {{ $pedido->estado_formateado }}
                                    </span>
                                </td>
                                <td>{{ $pedido->tiempo_transcurrido }}</td>
                                
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No hay pedidos recientes
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos Top -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-trophy"></i> Productos Más Vendidos
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($productosTop as $producto)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $producto->nombre }}
                        <span class="badge bg-primary rounded-pill">{{ $producto->total_vendido }}</span>
                    </li>
                    @empty
                    <li class="list-group-item text-center text-muted py-4">
                        No hay datos disponibles
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Accesos Rápidos -->
        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-lightning"></i> Accesos Rápidos
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.productos.create') }}" class="btn btn-outline-primary">
                        <i class="bi bi-plus-circle"></i> Nuevo Producto
                    </a>
                    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-outline-success">
                        <i class="bi bi-person-plus"></i> Nuevo Usuario
                    </a>
                    <a href="{{ route('admin.reportes.ventas') }}" class="btn btn-outline-info">
                        <i class="bi bi-graph-up"></i> Reporte de Ventas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function verPedido(id) {
    // Aquí se puede implementar un modal para ver detalles del pedido
    window.open(`/pos/pedido/${id}`, '_blank');
}
</script>
@endsection
