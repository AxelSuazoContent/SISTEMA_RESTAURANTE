@extends('layouts.app')

@section('title', 'Vista de Cocina')

@section('styles')
<style>
    .pedido-card {
        border-left: 5px solid #ffc107;
        margin-bottom: 15px;
        transition: all 0.3s;
    }
    .pedido-card.preparando {
        border-left-color: #17a2b8;
    }
    .pedido-card.listo {
        border-left-color: #28a745;
    }
    .pedido-card.urgente {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); }
        50% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
    }
    .tiempo {
        font-weight: bold;
        font-size: 1.2rem;
    }
    .tiempo.urgente {
        color: #dc3545;
    }
    .item-pedido {
        padding: 8px;
        margin: 4px 0;
        border-radius: 4px;
        background-color: #f8f9fa;
    }
    .item-pedido.preparando {
        background-color: #d1ecf1;
    }
    .item-pedido.listo {
        background-color: #d4edda;
        text-decoration: line-through;
        opacity: 0.7;
    }
    .stats-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        border-top: 1px solid #dee2e6;
        padding: 10px;
        z-index: 1000;
    }
    .stat-item {
        text-align: center;
        padding: 0 20px;
    }
    .stat-number {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .stat-label {
        font-size: 0.8rem;
        color: #6c757d;
    }
    .btn-accion {
        min-width: 100px;
    }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-fire"></i> Vista de Cocina</h1>
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" id="autoRefresh" checked>
        <label class="form-check-label" for="autoRefresh">Actualización automática</label>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['pendientes'] }}</h3>
                <small>Pendientes</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['preparando'] }}</h3>
                <small>En Preparación</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['listos'] }}</h3>
                <small>Listos</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3 class="mb-0">{{ $stats['items_pendientes'] }}</h3>
                <small>Items Pendientes</small>
            </div>
        </div>
    </div>
</div>

<!-- Pedidos Pendientes -->
<div class="row" id="pedidosContainer">
    @forelse($pedidosPendientes as $pedido)
    @php
        $minutos = $pedido->created_at->diffInMinutes(now());
        $urgente = $minutos > 20;
    @endphp
    <div class="col-md-6 col-lg-4 pedido-wrapper" data-pedido-id="{{ $pedido->id }}">
        <div class="card pedido-card {{ $pedido->estado }} {{ $urgente ? 'urgente' : '' }}">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>
                        @if($pedido->mesa)
                            Mesa {{ $pedido->mesa->numero }}
                        @else
                            {{ $pedido->tipo_formateado }}
                        @endif
                    </strong>
                    <span class="badge bg-{{ $pedido->estado_color }}">{{ $pedido->estado_formateado }}</span>
                </div>
                <div class="tiempo {{ $urgente ? 'urgente' : '' }}">
                    {{ $pedido->tiempo_transcurrido }}
                </div>
            </div>
            <div class="card-body">
                @if($pedido->notas)
                    <div class="alert alert-info py-1 mb-2">
                        <small><i class="bi bi-info-circle"></i> {{ $pedido->notas }}</small>
                    </div>
                @endif
                
                <div class="items-list">
                    @foreach($pedido->detalles as $detalle)
                    <div class="item-pedido {{ $detalle->estado }} d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $detalle->cantidad }}x</strong> {{ $detalle->producto->nombre }}
                            @if($detalle->notas)
                                <br><small class="text-muted">{{ $detalle->notas }}</small>
                            @endif
                        </div>
                        <div>
                            @if($detalle->estado === 'pendiente')
                                <button class="btn btn-sm btn-info btn-accion" 
                                        onclick="iniciarPreparacion({{ $detalle->id }})">
                                    <i class="bi bi-play"></i> Iniciar
                                </button>
                            @elseif($detalle->estado === 'preparando')
                                <button class="btn btn-sm btn-success btn-accion" 
                                        onclick="marcarListo({{ $detalle->id }})">
                                    <i class="bi bi-check-lg"></i> Listo
                                </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        #{{ $pedido->id }} - {{ $pedido->created_at->format('H:i') }}
                    </small>
                    @if($pedido->estado !== 'listo')
                    <button class="btn btn-success btn-sm" onclick="marcarPedidoListo({{ $pedido->id }})">
                        <i class="bi bi-check-all"></i> Todo Listo
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="bi bi-check-circle" style="font-size: 4rem; color: #28a745;"></i>
        <h4 class="mt-3 text-muted">No hay pedidos pendientes</h4>
        <p class="text-muted">¡La cocina está al día!</p>
    </div>
    @endforelse
</div>

<!-- Pedidos Listos Recientes -->
@if($pedidosListos->count() > 0)
<div class="mt-5">
    <h4><i class="bi bi-check-all"></i> Pedidos Listos Recientes</h4>
    <div class="row">
        @foreach($pedidosListos as $pedido)
        <div class="col-md-4 col-lg-3">
            <div class="card border-success">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>
                                @if($pedido->mesa)
                                    Mesa {{ $pedido->mesa->numero }}
                                @else
                                    {{ $pedido->tipo_formateado }}
                                @endif
                            </strong>
                            <br>
                            <small class="text-muted">{{ $pedido->updated_at->format('H:i') }}</small>
                        </div>
                        <span class="badge bg-success">Listo</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Sonido de notificación (oculto) -->
<audio id="sonidoNotificacion" preload="auto">
    <source src="{{ asset('sounds/notification.mp3') }}" type="audio/mpeg">
</audio>
@endsection

@section('scripts')
<script>
let ultimoCheck = new Date().toISOString();
let autoRefreshInterval;

function iniciarPreparacion(detalleId) {
    fetch(`/cocina/detalle/${detalleId}/iniciar`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            actualizarVista();
        }
    });
}

function marcarListo(detalleId) {
    fetch(`/cocina/detalle/${detalleId}/listo`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            actualizarVista();
            if (data.pedido_completo) {
                reproducirSonido();
            }
        }
    });
}

function marcarPedidoListo(pedidoId) {
    if (!confirm('¿Marcar todo el pedido como listo?')) return;
    
    fetch(`/cocina/pedido/${pedidoId}/listo`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            reproducirSonido();
            actualizarVista();
        }
    });
}

function actualizarVista() {
    fetch('/cocina/pedidos-pendientes')
        .then(r => r.json())
        .then(data => {
            location.reload(); // Por simplicidad, recargamos la página
        });
}

function reproducirSonido() {
    const audio = document.getElementById('sonidoNotificacion');
    audio.play().catch(e => console.log('No se pudo reproducir el sonido'));
}

function checkNuevosPedidos() {
    fetch(`/cocina/check-nuevos?ultimo_check=${encodeURIComponent(ultimoCheck)}`)
        .then(r => r.json())
        .then(data => {
            if (data.nuevos) {
                reproducirSonido();
                actualizarVista();
            }
            ultimoCheck = data.timestamp;
        });
}

// Auto refresh
document.getElementById('autoRefresh').addEventListener('change', function() {
    if (this.checked) {
        iniciarAutoRefresh();
    } else {
        clearInterval(autoRefreshInterval);
    }
});

function iniciarAutoRefresh() {
    autoRefreshInterval = setInterval(() => {
        checkNuevosPedidos();
    }, 10000); // Cada 10 segundos
}

// Iniciar
iniciarAutoRefresh();

// También actualizar cada 30 segundos
setInterval(() => {
    if (document.getElementById('autoRefresh').checked) {
        actualizarVista();
    }
}, 30000);
</script>
@endsection
