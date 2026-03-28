

<?php $__env->startSection('title', 'Punto de Venta'); ?>

<?php $__env->startSection('styles'); ?>
<style>
    :root {
        --verde: #28a745;
        --rojo: #dc3545;
        --azul: #0d6efd;
        --gris: #6c757d;
    }

    .mesa-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
        gap: 10px;
    }
    .mesa-item {
        aspect-ratio: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.15s;
        font-weight: 700;
        font-size: 0.85rem;
        user-select: none;
    }
    .mesa-item:hover { transform: scale(1.07); box-shadow: 0 4px 14px rgba(0,0,0,.15); }
    .mesa-disponible { background: #d4edda; color: #155724; border: 2px solid #28a745; }
    .mesa-ocupada    { background: #f8d7da; color: #721c24; border: 2px solid #dc3545; }
    .mesa-reservada  { background: #fff3cd; color: #856404; border: 2px solid #ffc107; }
    .mesa-limpieza   { background: #d1ecf1; color: #0c5460; border: 2px solid #17a2b8; }
    .mesa-inactiva   { background: #e2e3e5; color: #6c757d; border: 2px solid #adb5bd; opacity:.6; cursor:not-allowed; }
    .mesa-seleccionada { box-shadow: 0 0 0 3px #0d6efd !important; transform: scale(1.07); }

    .categoria-tabs { overflow-x: auto; white-space: nowrap; padding-bottom: 4px; }
    .categoria-tabs .nav-link { border-radius: 20px; margin-right: 6px; color: #555; font-size: .85rem; }
    .categoria-tabs .nav-link.active { background: var(--azul); color: #fff !important; }

    .producto-item {
        cursor: pointer;
        transition: border-color .15s, transform .15s;
        border: 2px solid transparent;
        border-radius: 8px;
    }
    .producto-item:hover { border-color: var(--azul); transform: translateY(-2px); }
    .producto-item .precio { font-weight: 700; color: var(--verde); }
    .producto-sin-stock { opacity: .45; pointer-events: none; }

    #ticketPanel { max-height: calc(100vh - 340px); overflow-y: auto; }
    .ticket-item { border-bottom: 1px solid #eee; padding: 10px 0; }
    .ticket-item:last-child { border-bottom: none; }
    .cantidad-control { display: flex; align-items: center; gap: 5px; }
    .cantidad-control button {
        width: 26px; height: 26px; padding: 0;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
    }

    .badge-estado { font-size: .72rem; padding: 2px 8px; border-radius: 20px; font-weight: 600; }
    .estado-pendiente  { background:#fff3cd; color:#856404; }
    .estado-preparando { background:#cce5ff; color:#004085; }
    .estado-listo      { background:#d4edda; color:#155724; }
    .estado-entregado  { background:#e2e3e5; color:#383d41; }
    .estado-pagado     { background:#d4edda; color:#155724; }
    .estado-cancelado  { background:#f8d7da; color:#721c24; }

    #modalLiberarMesa .modal-header { background: #dc3545; color: #fff; }
    #modalLiberarMesa .btn-close { filter: invert(1); }

    .nav-pills .nav-link.active { background-color: #0d6efd !important; color: white !important; }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<?php if($cierreCaja): ?>
<div class="alert alert-danger d-flex align-items-center gap-2 mb-3" style="font-size:14px">
    <i class="bi bi-lock-fill" style="font-size:1.2rem"></i>
    <strong>Operaciones cerradas.</strong> No se pueden enviar nuevos pedidos.
</div>
<?php endif; ?>

<div class="row g-3">

    
    <div class="col-lg-8">

        
        <div class="card mb-3">
            <div class="card-body py-2">
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="tipoPedido" id="tipoMesa" value="mesa" checked>
                    <label class="btn btn-outline-primary" for="tipoMesa">
                        <i class="bi bi-grid-3x3"></i> En Mesa
                    </label>
                    <input type="radio" class="btn-check" name="tipoPedido" id="tipoLlevar" value="llevar">
                    <label class="btn btn-outline-primary" for="tipoLlevar">
                        <i class="bi bi-bag"></i> Para Llevar
                    </label>
                    <input type="radio" class="btn-check" name="tipoPedido" id="tipoDomicilio" value="domicilio">
                    <label class="btn btn-outline-primary" for="tipoDomicilio">
                        <i class="bi bi-house"></i> A Domicilio
                    </label>
                </div>
            </div>
        </div>

        
        <div class="card mb-3" id="mesaSelector">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-grid-3x3"></i> Seleccionar Mesa</span>
                <small class="text-muted">
                    <span class="badge bg-success">●</span> Disponible &nbsp;
                    <span class="badge bg-danger">●</span> Ocupada
                </small>
            </div>
            <div class="card-body">
                

                <div class="mesa-grid">
                    
<?php $__currentLoopData = $mesas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mesa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php
    $reservasMesa   = $reservaciones->get($mesa->id, collect());
    $proximaReserva = $reservasMesa->first();
?>
<div class="position-relative">
    <div class="mesa-item mesa-<?php echo e($mesa->estado); ?>"
         id="mesa-<?php echo e($mesa->id); ?>"
         data-mesa-id="<?php echo e($mesa->id); ?>"
         data-mesa-numero="<?php echo e($mesa->numero); ?>"
         data-mesa-estado="<?php echo e($mesa->estado); ?>">
        <i class="bi bi-<?php echo e($mesa->capacidad > 4 ? 'people' : 'person'); ?>" style="font-size:1.4rem"></i>
        <span><?php echo e($mesa->numero); ?></span>
        <small><?php echo e($mesa->capacidad); ?>p</small>
    </div>
    <?php if($proximaReserva): ?>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark"
          style="font-size:.6rem; cursor:pointer; z-index:10;"
          onclick="event.stopPropagation(); verReservaciones(<?php echo e($mesa->id); ?>)">
        <i class="bi bi-calendar-check"></i> <?php echo e($reservasMesa->count()); ?>

    </span>
    <?php endif; ?>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        
        <div class="card mb-3 d-none" id="clienteDatos">
            <div class="card-header"><i class="bi bi-person"></i> Datos del Cliente</div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="clienteNombre" placeholder="Nombre del cliente">
                    </div>
                    <div class="col-md-6">
                        <input type="tel" class="form-control" id="clienteTelefono" placeholder="Teléfono">
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card mb-3">
            <div class="card-body p-2">
                <div class="categoria-tabs">
                    <ul class="nav nav-pills flex-nowrap" id="categoriasTab">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" data-categoria="todas"
                               style="background-color: #6c757d20; color: #6c757d;">Todas</a>
                        </li>
                        <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-categoria="<?php echo e($cat->id); ?>"
                               style="background:<?php echo e($cat->color); ?>22; color:<?php echo e($cat->color); ?>">
                                <?php echo e($cat->nombre); ?>

                            </a>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-body">
                <div class="row g-3" id="productosGrid">
                    <?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $__currentLoopData = $cat->productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-6 col-md-4 col-lg-3 producto-wrapper" data-categoria="<?php echo e($cat->id); ?>">
                            <div class="card producto-item h-100 <?php echo e($prod->stock <= 0 ? 'producto-sin-stock' : ''); ?>"
                                 onclick="agregarProducto(<?php echo e($prod->id); ?>, '<?php echo e(addslashes($prod->nombre)); ?>', <?php echo e($prod->precio); ?>)">
                                <?php if($prod->imagen): ?>
                                    <img src="<?php echo e(asset('storage/'.$prod->imagen)); ?>"
                                         class="card-img-top" alt="<?php echo e($prod->nombre); ?>"
                                         style="height:90px;object-fit:cover">
                                <?php else: ?>
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                         style="height:90px">
                                        <i class="bi bi-image text-muted" style="font-size:2rem"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body p-2 text-center">
                                    <h6 class="card-title mb-1" style="font-size:.85rem"><?php echo e($prod->nombre); ?></h6>
                                    <p class="precio mb-0">$<?php echo e(number_format($prod->precio,2)); ?></p>
                                    <?php if($prod->stock <= 0): ?>
                                        <small class="text-danger">Sin stock</small>
                                    <?php elseif($prod->stock < 10): ?>
                                        <small class="text-warning">Stock: <?php echo e($prod->stock); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="card sticky-top" style="top:16px">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-receipt"></i> Ticket</span>
                    <span id="infoMesa" class="badge bg-white text-primary">Sin mesa</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="ticketPanel" class="p-3">
                    <div id="ticketVacio" class="text-center text-muted py-5">
                        <i class="bi bi-cart" style="font-size:3rem"></i>
                        <p class="mt-2">Agrega productos al ticket</p>
                    </div>
                    <div id="ticketItems"></div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted">Subtotal:</span>
                    <strong id="subtotal">$0.00</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Total:</span>
                    <h4 class="mb-0 text-success" id="total">$0.00</h4>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-danger" id="btnCancelar" onclick="cancelarTicket()" disabled>
                        <i class="bi bi-x-lg"></i> Limpiar Ticket
                    </button>
                    <button class="btn btn-success btn-lg" id="btnEnviar" onclick="enviarPedido()" disabled>
                        <i class="bi bi-send"></i> Enviar Pedido
                    </button>
                    <?php if($cierreCaja): ?>
                    <div class="alert alert-warning py-2 text-center mb-0" style="font-size:13px">
                        <i class="bi bi-lock me-1"></i> Operaciones cerradas
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history"></i> Pedidos Activos</span>
                <span class="badge bg-primary"><?php echo e($pedidosActivos->count()); ?></span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush" id="listaPedidosActivos">
                    <?php $__empty_1 = true; $__currentLoopData = $pedidosActivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="#" class="list-group-item list-group-item-action"
                       onclick="verDetallePedido(<?php echo e($pedido->id); ?>)">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-1">
                                <?php if($pedido->mesa): ?> Mesa <?php echo e($pedido->mesa->numero); ?>

                                <?php else: ?> <?php echo e($pedido->cliente_nombre ?: 'Sin nombre'); ?>

                                <?php endif; ?>
                            </h6>
                            <small class="text-muted"><?php echo e($pedido->tiempo_transcurrido); ?></small>
                        </div>
                        <p class="mb-1 fw-bold">$<?php echo e(number_format($pedido->total,2)); ?></p>
                        <span class="badge-estado estado-<?php echo e($pedido->estado); ?>">
                            <?php echo e($pedido->estado_formateado); ?>

                        </span>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="list-group-item text-center text-muted py-3">
                        No hay pedidos activos
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalLiberarMesa" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle"></i> Mesa Ocupada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-table" style="font-size:3rem;color:#dc3545"></i>
                <h5 class="mt-3" id="modalMesaTitulo">Mesa 0</h5>
                <p class="text-muted">Esta mesa tiene un pedido activo. ¿Qué deseas hacer?</p>
            </div>
            <div class="modal-footer flex-column gap-2">
                <button class="btn btn-primary w-100" id="btnVerPedidoMesa">
                    <i class="bi bi-eye"></i> Ver pedido activo
                </button>
                
                <button class="btn btn-secondary w-100" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalReservaciones" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="bi bi-calendar-check"></i> Reservaciones de la Mesa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" id="modalReservacionesCuerpo"></div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetallePedido" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-receipt"></i> Detalle del Pedido</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalDetalleCuerpo">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                </div>
            </div>
            <div class="modal-footer">
    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
    <button class="btn btn-danger" id="btnCancelarPedido">
        <i class="bi bi-x-circle"></i> Cancelar Pedido
    </button>
    <button class="btn btn-success" id="btnCobrarPedido">
        <i class="bi bi-cash"></i> Cobrar
    </button>
</div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalCobrar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-cash-coin"></i> Cobrar Pedido</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center">
                    <p class="text-muted mb-1">Total a Pagar</p>
                    <h2 class="text-success" id="totalCobrar">$0.00</h2>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Método de Pago</label>
                    <select class="form-select" id="metodoPago">
                        <option value="efectivo">💵 Efectivo</option>
                        <option value="tarjeta">💳 Tarjeta</option>
                        <option value="transferencia">🏦 Transferencia</option>
                        <option value="otro">📋 Otro</option>
                    </select>
                </div>
                <div id="campoEfectivo">
                    <label class="form-label fw-semibold">Monto Recibido</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text">$</span>
                        <input type="number" class="form-control form-control-lg" id="montoRecibido" step="0.01" min="0">
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                        <span class="fw-semibold">Cambio:</span>
                        <h4 class="mb-0 text-primary" id="cambio">$0.00</h4>
                    </div>
                </div>
                <div id="campoReferencia" class="d-none">
                    <label class="form-label fw-semibold">Referencia / Número de operación</label>
                    <input type="text" class="form-control" id="referenciaPago" placeholder="Opcional">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success btn-lg px-4" onclick="procesarPago()">
                    <i class="bi bi-check-circle"></i> Confirmar Pago
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
// Variable global de cierre de caja desde PHP
const CAJA_CERRADA = <?php echo e($cierreCaja ? 'true' : 'false'); ?>;

let ticket           = [];
let mesaSeleccionada = null;
let pedidoActual     = null;
let mesaModal        = { id: null, numero: null };

// Tipo de pedido
document.querySelectorAll('input[name="tipoPedido"]').forEach(radio => {
    radio.addEventListener('change', function () {
        const tipo = this.value;
        if (tipo === 'mesa') {
            document.getElementById('mesaSelector').classList.remove('d-none');
            document.getElementById('clienteDatos').classList.add('d-none');
        } else {
            document.getElementById('mesaSelector').classList.add('d-none');
            document.getElementById('clienteDatos').classList.remove('d-none');
            deseleccionarMesa();
            document.getElementById('infoMesa').textContent =
                tipo === 'llevar' ? 'Para llevar' : 'A domicilio';
        }
    });
});

// Filtro categorías
document.querySelectorAll('#categoriasTab .nav-link').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelectorAll('#categoriasTab .nav-link').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        const cat = this.dataset.categoria;
        document.querySelectorAll('.producto-wrapper').forEach(w => {
            w.classList.toggle('d-none', cat !== 'todas' && w.dataset.categoria !== cat);
        });
    });
});

// Mesas
function seleccionarMesa(id, numero, estado, horaReserva) {
    if (CAJA_CERRADA) {
        mostrarToast('Las operaciones están cerradas.', 'warning');
        return;
    }
    if (estado === 'ocupada') {
        mesaModal = { id, numero };
        document.getElementById('modalMesaTitulo').textContent = `Mesa ${numero}`;
        new bootstrap.Modal(document.getElementById('modalLiberarMesa')).show();
        return;
    }
    if (estado === 'inactiva') {
        mostrarToast('Esta mesa está inactiva y no puede usarse', 'warning');
        return;
    }
    if (estado === 'reservada' && horaReserva) {
        const ahora   = new Date();
        const partes  = horaReserva.split(':');
        const reserva = new Date();
        reserva.setHours(parseInt(partes[0]), parseInt(partes[1]), 0);
        const diffMin = (reserva - ahora) / 60000;
        if (diffMin > 30) {
            mostrarToast(`Mesa reservada a las ${horaReserva}. Disponible 30 min antes.`, 'warning');
            return;
        }
    }
    deseleccionarMesa();
    mesaSeleccionada = id;
    document.getElementById('infoMesa').textContent = 'Mesa ' + numero;
    document.querySelector(`[data-mesa-id="${id}"]`).classList.add('mesa-seleccionada');
}

function deseleccionarMesa() {
    document.querySelectorAll('.mesa-item').forEach(el => {
        el.classList.remove('mesa-seleccionada');
        el.style.opacity = '1';
    });
    mesaSeleccionada = null;
}

document.getElementById('btnVerPedidoMesa').addEventListener('click', function () {
    bootstrap.Modal.getInstance(document.getElementById('modalLiberarMesa')).hide();
    verDetallePedidoPorMesa(mesaModal.id);
});

// Registrar clicks de mesas via addEventListener
document.querySelectorAll('.mesa-item').forEach(el => {
    el.addEventListener('click', function() {
        const id     = parseInt(this.dataset.mesaId);
        const num    = parseInt(this.dataset.mesaNumero);
        const estado = this.dataset.mesaEstado;
        seleccionarMesa(id, num, estado);
    });
});


// Ticket
function agregarProducto(id, nombre, precio) {
    if (CAJA_CERRADA) {
        mostrarToast('Las operaciones están cerradas.', 'warning');
        return;
    }
    const existente = ticket.find(i => i.id === id);
    if (existente) {
        existente.cantidad++;
    } else {
        ticket.push({ id, nombre, precio, cantidad: 1, notas: '' });
    }
    actualizarTicket();
}

function cambiarCantidad(index, delta) {
    ticket[index].cantidad += delta;
    if (ticket[index].cantidad <= 0) ticket.splice(index, 1);
    actualizarTicket();
}

function eliminarItem(index) {
    ticket.splice(index, 1);
    actualizarTicket();
}

function actualizarNota(index, nota) {
    ticket[index].notas = nota;
}

function actualizarTicket() {
    const container = document.getElementById('ticketItems');
    const vacio     = document.getElementById('ticketVacio');
    const vacio2    = ticket.length === 0;

    vacio.classList.toggle('d-none', !vacio2);
    // Si la caja está cerrada, el botón siempre deshabilitado
    document.getElementById('btnEnviar').disabled  = vacio2 || CAJA_CERRADA;
    document.getElementById('btnCancelar').disabled = vacio2;

    if (!vacio2) {
        container.innerHTML = ticket.map((item, i) => `
            <div class="ticket-item">
                <div class="d-flex justify-content-between align-items-start">
                    <div style="max-width:55%">
                        <strong>${item.nombre}</strong>
                        <div class="text-muted small">$${item.precio.toFixed(2)} c/u</div>
                    </div>
                    <div class="text-end">
                        <div class="cantidad-control justify-content-end">
                            <button class="btn btn-sm btn-outline-secondary" onclick="cambiarCantidad(${i},-1)">−</button>
                            <span class="mx-1">${item.cantidad}</span>
                            <button class="btn btn-sm btn-outline-secondary" onclick="cambiarCantidad(${i},1)">+</button>
                        </div>
                        <div class="mt-1 fw-bold">$${(item.precio * item.cantidad).toFixed(2)}</div>
                    </div>
                </div>
                <input type="text" class="form-control form-control-sm mt-1"
                       placeholder="Notas (opcional)"
                       value="${item.notas}"
                       onchange="actualizarNota(${i}, this.value)">
                <button class="btn btn-sm btn-link text-danger p-0 mt-1" onclick="eliminarItem(${i})">
                    <i class="bi bi-trash"></i> Quitar
                </button>
            </div>
        `).join('');
    } else {
        container.innerHTML = '';
    }

    calcularTotales();
}

function calcularTotales() {
    const sub = ticket.reduce((s, i) => s + i.precio * i.cantidad, 0);
    document.getElementById('subtotal').textContent = '$' + sub.toFixed(2);
    document.getElementById('total').textContent    = '$' + sub.toFixed(2);
}

function cancelarTicket() {
    if (!confirm('¿Limpiar el ticket?')) return;
    ticket = [];
    pedidoActual = null;
    actualizarTicket();
    deseleccionarMesa();
    document.getElementById('infoMesa').textContent = 'Sin mesa';
}

function enviarPedido() {
    if (CAJA_CERRADA) {
        mostrarToast('Las operaciones están cerradas. No se pueden enviar pedidos.', 'warning');
        return;
    }

    const tipo = document.querySelector('input[name="tipoPedido"]:checked').value;

    if (tipo === 'mesa' && !mesaSeleccionada) {
        mostrarToast('Selecciona una mesa primero', 'warning');
        return;
    }
    if (ticket.length === 0) {
        mostrarToast('Agrega productos al ticket', 'warning');
        return;
    }

    const btnEnviar = document.getElementById('btnEnviar');
    btnEnviar.disabled = true;
    btnEnviar.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enviando...';

    const data = {
        tipo,
        mesa_id: mesaSeleccionada,
        cliente_nombre:   document.getElementById('clienteNombre')?.value  || '',
        cliente_telefono: document.getElementById('clienteTelefono')?.value || '',
        productos: ticket.map(i => ({ id: i.id, cantidad: i.cantidad, notas: i.notas }))
    };

    fetch('<?php echo e(route("pos.pedido.crear")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type':  'application/json',
            'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            if (mesaSeleccionada) {
                const el = document.querySelector(`[data-mesa-id="${mesaSeleccionada}"]`);
                el.classList.remove('mesa-disponible', 'mesa-seleccionada');
                el.classList.add('mesa-ocupada');
                el.dataset.mesaEstado = 'ocupada';
            }
            mostrarToast('¡Pedido enviado correctamente!', 'success');
            ticket = [];
            mesaSeleccionada = null;
            pedidoActual = null;
            actualizarTicket();
            document.getElementById('infoMesa').textContent = 'Sin mesa';
            setTimeout(() => location.reload(), 1200);
        } else {
            mostrarToast(res.message || 'Error al enviar el pedido', 'danger');
            btnEnviar.disabled = false;
            btnEnviar.innerHTML = '<i class="bi bi-send"></i> Enviar Pedido';
        }
    })
    .catch(() => {
        mostrarToast('Error de conexión', 'danger');
        btnEnviar.disabled = false;
        btnEnviar.innerHTML = '<i class="bi bi-send"></i> Enviar Pedido';
    });
}

function verDetallePedido(id) {
    pedidoActual = { id };
    document.getElementById('modalDetalleCuerpo').innerHTML =
        '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
    new bootstrap.Modal(document.getElementById('modalDetallePedido')).show();
    fetch(`/pos/pedido/${id}`)
        .then(r => r.json())
        .then(data => {
            pedidoActual = data.pedido;
            renderDetallePedido(data.pedido);
        })
        .catch(() => {
            document.getElementById('modalDetalleCuerpo').innerHTML =
                '<p class="text-danger text-center">Error al cargar el pedido.</p>';
        });
}

function verDetallePedidoPorMesa(mesaId) {
    bootstrap.Modal.getInstance(document.getElementById('modalLiberarMesa'))?.hide();
    document.getElementById('modalDetalleCuerpo').innerHTML =
        '<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>';
    new bootstrap.Modal(document.getElementById('modalDetallePedido')).show();

    fetch(`/pos/mesa/${mesaId}/pedido-activo`)
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                pedidoActual = data.pedido;
                renderDetallePedido(data.pedido);
            } else {
                document.getElementById('modalDetalleCuerpo').innerHTML =
                    '<p class="text-warning text-center">No se encontró un pedido activo para esta mesa.</p>';
            }
        })
        .catch(() => {
            document.getElementById('modalDetalleCuerpo').innerHTML =
                '<p class="text-danger text-center">Error al cargar el pedido.</p>';
        });
}

function renderDetallePedido(p) {
    const items = p.detalles.map(d => `
        <tr>
            <td>${d.producto.nombre}</td>
            <td class="text-center">${d.cantidad}</td>
            <td class="text-end">$${parseFloat(d.precio_unitario).toFixed(2)}</td>
            <td class="text-end fw-bold">$${(d.cantidad * d.precio_unitario).toFixed(2)}</td>
        </tr>
        ${d.notas ? `<tr><td colspan="4"><small class="text-muted">📝 ${d.notas}</small></td></tr>` : ''}
    `).join('');

    document.getElementById('modalDetalleCuerpo').innerHTML = `
        <div class="row mb-3">
            <div class="col-6">
                <small class="text-muted">Mesa / Cliente</small>
                <p class="fw-bold mb-0">${p.mesa ? 'Mesa '+p.mesa.numero : (p.cliente_nombre || '—')}</p>
            </div>
            <div class="col-6 text-end">
                <small class="text-muted">Estado</small>
                <p><span class="badge-estado estado-${p.estado}">${p.estado_formateado || p.estado}</span></p>
            </div>
        </div>
        <table class="table table-sm">
            <thead class="table-light">
                <tr>
                    <th>Producto</th>
                    <th class="text-center">Cant.</th>
                    <th class="text-end">Precio</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>${items}</tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td colspan="3" class="text-end">TOTAL</td>
                    <td class="text-end text-success">$${parseFloat(p.total).toFixed(2)}</td>
                </tr>
            </tfoot>
        </table>
        ${p.notas ? `<div class="alert alert-light"><small>📝 ${p.notas}</small></div>` : ''}
    `;

    const btnCobrar   = document.getElementById('btnCobrarPedido');
const btnCancelar = document.getElementById('btnCancelarPedido');

if (p.estado === 'pagado' || p.estado === 'cancelado') {
    btnCobrar.classList.add('d-none');
    btnCancelar.classList.add('d-none');
} else {
    btnCobrar.classList.remove('d-none');
    btnCobrar.onclick = () => abrirModalCobrar(p.id, p.total);

    btnCancelar.classList.remove('d-none');
    btnCancelar.onclick = () => cancelarPedido(p.id);
}
}

function abrirModalCobrar(pedidoId, total) {
    bootstrap.Modal.getInstance(document.getElementById('modalDetallePedido'))?.hide();
    pedidoActual = { id: pedidoId, total };
    document.getElementById('totalCobrar').textContent = '$' + parseFloat(total).toFixed(2);
    document.getElementById('montoRecibido').value = '';
    document.getElementById('cambio').textContent = '$0.00';
    document.getElementById('referenciaPago').value = '';
    document.getElementById('metodoPago').value = 'efectivo';
    document.getElementById('campoEfectivo').classList.remove('d-none');
    document.getElementById('campoReferencia').classList.add('d-none');
    setTimeout(() => new bootstrap.Modal(document.getElementById('modalCobrar')).show(), 300);
}

document.getElementById('metodoPago').addEventListener('change', function () {
    const esEfectivo = this.value === 'efectivo';
    document.getElementById('campoEfectivo').classList.toggle('d-none', !esEfectivo);
    document.getElementById('campoReferencia').classList.toggle('d-none', esEfectivo);
});

document.getElementById('montoRecibido').addEventListener('input', function () {
    const total    = parseFloat(document.getElementById('totalCobrar').textContent.replace('$','')) || 0;
    const recibido = parseFloat(this.value) || 0;
    const cambio   = recibido - total;
    document.getElementById('cambio').textContent = '$' + (cambio > 0 ? cambio.toFixed(2) : '0.00');
});

function procesarPago() {
    if (!pedidoActual) return;
    const metodo   = document.getElementById('metodoPago').value;
    const total    = parseFloat(document.getElementById('totalCobrar').textContent.replace('$',''));
    const recibido = parseFloat(document.getElementById('montoRecibido').value) || 0;

    if (metodo === 'efectivo' && recibido < total) {
        mostrarToast('El monto recibido es menor al total', 'warning');
        return;
    }

    fetch(`/pos/pedido/${pedidoActual.id}/pagar`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            metodo_pago:    metodo,
            monto_recibido: metodo === 'efectivo' ? recibido : total,
            referencia:     document.getElementById('referenciaPago').value
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalCobrar'))?.hide();
            mostrarToast('¡Pago procesado correctamente!', 'success');
            fetch(`/pos/pedido/${pedidoActual.id}/factura`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(r => r.json())
            .then(f => {
                if (f.success) {
                    mostrarToast(`Factura ${f.numero} generada`, 'success');
                    setTimeout(() => window.open(f.imprimir_url, '_blank'), 800);
                }
            });
            setTimeout(() => location.reload(), 2000);
        } else {
            mostrarToast(data.message || 'Error al procesar el pago', 'danger');
        }
    })
    .catch(() => mostrarToast('Error de conexión', 'danger'));
}

function mostrarToast(mensaje, tipo = 'info') {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:8px';
        document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    toast.className = `alert alert-${tipo} shadow mb-0 py-2 px-3`;
    toast.style.cssText = 'min-width:260px;animation:fadeIn .2s ease';
    toast.innerHTML = `<i class="bi bi-${tipo==='success'?'check-circle':tipo==='danger'?'x-circle':'exclamation-circle'}"></i> ${mensaje}`;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}

function cancelarPedido(id) {
    if (!confirm('¿Estás seguro de que deseas cancelar este pedido?')) return;

    fetch(`/pos/pedido/${id}/cancelar`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ motivo: 'Cancelado desde POS' })
})
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalDetallePedido'))?.hide();
            mostrarToast('Pedido cancelado correctamente.', 'success');
            setTimeout(() => location.reload(), 1200);
        } else {
            mostrarToast(data.message || 'No se pudo cancelar el pedido.', 'danger');
        }
    })
    .catch(() => mostrarToast('Error de conexión.', 'danger'));
}

function verReservaciones(mesaId) {
    const lista = RESERVACIONES[mesaId] || [];
    let html = '<ul class="list-group list-group-flush">';

    lista.forEach(r => {
        const hora = r.hora.substring(0, 5);
        html += `
            <li class="list-group-item py-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <strong>${r.cliente_nombre || 'Sin nombre'}</strong>
                        ${r.cliente_telefono
                            ? `<br><small class="text-muted"><i class="bi bi-telephone"></i> ${r.cliente_telefono}</small>`
                            : ''}
                        ${r.notas
                            ? `<br><small class="text-muted"><i class="bi bi-chat-left-text"></i> ${r.notas}</small>`
                            : ''}
                    </div>
                    <span class="badge bg-warning text-dark ms-2 text-end">
                        <i class="bi bi-calendar"></i> ${r.fecha}<br>
                        <i class="bi bi-clock"></i> ${hora}
                    </span>
                </div>
            </li>`;
    });

    html += '</ul>';

    if (lista.length === 0) {
        html = '<p class="text-center text-muted py-4">No hay reservaciones vigentes.</p>';
    }

    document.getElementById('modalReservacionesCuerpo').innerHTML = html;
    new bootstrap.Modal(document.getElementById('modalReservaciones')).show();
}

const RESERVACIONES = <?php echo json_encode($reservaciones, 15, 512) ?>;
</script>

<style>
@keyframes fadeIn { from { opacity:0; transform:translateX(20px); } to { opacity:1; transform:none; } }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Ryzen Gaming\Documents\aaa\SISTEMA_RESTAURANTE\restaurante-laravel\resources\views/pos/index.blade.php ENDPATH**/ ?>