<?php $__env->startSection('title', 'Cierre de Caja'); ?>

<?php $__env->startSection('styles'); ?>
<style>
@media print {
    .no-print { display: none !important; }
    .navbar, .sidebar { display: none !important; }
    main.col-md-10 { width: 100% !important; margin: 0 !important; padding: 0 !important; }
    .col-md-10.ms-sm-auto { margin-left: 0 !important; }
}
.stat-box { border-radius: 12px; padding: 1.25rem 1.5rem; }
.caja-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 99px;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <div class="d-flex gap-2 no-print">
    <button onclick="window.print()" class="btn btn-outline-primary">
        <i class="bi bi-printer"></i> Imprimir
    </button>
    <button onclick="abrirOperaciones()" class="btn btn-success">
        <i class="bi bi-unlock"></i> Abrir Operaciones
    </button>
    <button onclick="cerrarOperaciones()" class="btn btn-danger">
        <i class="bi bi-lock"></i> Cerrar Operaciones
    </button>
</div>
</div>


<div class="card mb-4 no-print" style="border-radius:12px;border:1px solid #e9ecef">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <?php if($apertura): ?>
                    <span class="caja-badge bg-success text-white">
                        <i class="bi bi-unlock"></i> Caja Abierta
                    </span>
                    <div>
                        <div style="font-size:13px;color:#6c757d">Abierta por <?php echo e($apertura->usuario->nombre); ?> a las <?php echo e($apertura->apertura_at->format('H:i')); ?></div>
                        <div style="font-size:15px;font-weight:600">Monto inicial: <span class="text-success">$<?php echo e(number_format($apertura->monto_inicial, 2)); ?></span></div>
                        <?php if($totalEsperado !== null): ?>
                            <div style="font-size:13px;color:#6c757d">
                                En caja ahora debería haber: <strong class="text-dark">$<?php echo e(number_format($totalEsperado, 2)); ?></strong>
                                <span class="text-muted">(inicial + ventas en efectivo)</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <span class="caja-badge bg-danger text-white">
                        <i class="bi bi-lock"></i> Caja Cerrada
                    </span>
                    <div style="font-size:13px;color:#6c757d">No se ha abierto caja hoy</div>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2">
                <?php if(!$apertura): ?>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAbrirCaja">
                        <i class="bi bi-unlock"></i> Abrir Caja
                    </button>
                <?php else: ?>
                    <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalCerrarCaja">
                        <i class="bi bi-lock"></i> Cerrar Caja
                    </button>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($apertura && $apertura->cierre_at): ?>
        <div class="mt-3 p-3 rounded" style="background:#f8f9fa;border:1px solid #e9ecef">
            <div class="row g-3">
                <div class="col-md-3 text-center">
                    <div style="font-size:12px;color:#6c757d">Monto inicial</div>
                    <div style="font-size:20px;font-weight:700">$<?php echo e(number_format($apertura->monto_inicial, 2)); ?></div>
                </div>
                <div class="col-md-3 text-center">
                    <div style="font-size:12px;color:#6c757d">Monto final contado</div>
                    <div style="font-size:20px;font-weight:700">$<?php echo e(number_format($apertura->monto_final, 2)); ?></div>
                </div>
                <div class="col-md-3 text-center">
                    <div style="font-size:12px;color:#6c757d">Ventas del día</div>
                    <div style="font-size:20px;font-weight:700 text-success">$<?php echo e(number_format($apertura->ventas_dia, 2)); ?></div>
                </div>
                <div class="col-md-3 text-center">
                    <div style="font-size:12px;color:#6c757d">Diferencia</div>
                    <div style="font-size:20px;font-weight:700;color:<?php echo e($apertura->diferencia >= 0 ? '#198754' : '#dc3545'); ?>">
                        <?php echo e($apertura->diferencia >= 0 ? '+' : ''); ?>$<?php echo e(number_format($apertura->diferencia, 2)); ?>

                    </div>
                    <div style="font-size:11px;color:#6c757d">
                        <?php echo e($apertura->diferencia > 0 ? 'Sobrante' : ($apertura->diferencia < 0 ? 'Faltante' : 'Exacto')); ?>

                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-box bg-success text-white">
            <div style="font-size:12px;font-weight:600;opacity:.8">Efectivo</div>
            <div style="font-size:26px;font-weight:700">$<?php echo e(number_format($totalEfectivo, 2)); ?></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box bg-primary text-white">
            <div style="font-size:12px;font-weight:600;opacity:.8">Tarjeta</div>
            <div style="font-size:26px;font-weight:700">$<?php echo e(number_format($totalTarjeta, 2)); ?></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box bg-info text-white">
            <div style="font-size:12px;font-weight:600;opacity:.8">Transferencia</div>
            <div style="font-size:26px;font-weight:700">$<?php echo e(number_format($totalTransferencia, 2)); ?></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box bg-warning text-dark">
            <div style="font-size:12px;font-weight:600;opacity:.8">Otro</div>
            <div style="font-size:26px;font-weight:700">$<?php echo e(number_format($totalOtro, 2)); ?></div>
        </div>
    </div>
</div>


<div class="card mb-4" style="border-radius:12px;border:2px solid #198754">
    <div class="card-body d-flex justify-content-between align-items-center">
        <div>
            <div class="text-muted" style="font-size:13px">Total del día — <?php echo e($totalPedidos); ?> pedidos</div>
            <div style="font-size:32px;font-weight:800;color:#198754">$<?php echo e(number_format($totalDia, 2)); ?></div>
        </div>
        <i class="bi bi-cash-coin" style="font-size:3rem;color:#198754;opacity:.3"></i>
    </div>
</div>


<div class="card" style="border-radius:12px;border:1px solid #e9ecef">
    <div class="card-body p-0">
        <div class="p-3 border-bottom">
            <h6 class="mb-0 fw-semibold"><i class="bi bi-list me-1"></i> Ventas del Día</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:13px">
                <thead style="background:#f8f9fa">
                    <tr>
                        <th style="padding:10px 16px"># Pedido</th>
                        <th style="padding:10px 16px">Hora</th>
                        <th style="padding:10px 16px">Mesa / Cliente</th>
                        <th style="padding:10px 16px">Método</th>
                        <th style="padding:10px 16px" class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="padding:10px 16px" class="text-muted">#<?php echo e($venta->id); ?></td>
                        <td style="padding:10px 16px"><?php echo e($venta->created_at->format('H:i')); ?></td>
                        <td style="padding:10px 16px">
                            <?php if($venta->mesa): ?> Mesa <?php echo e($venta->mesa->numero); ?>

                            <?php else: ?> <?php echo e($venta->cliente_nombre ?: 'Sin nombre'); ?>

                            <?php endif; ?>
                        </td>
                        <td style="padding:10px 16px"><?php echo e(ucfirst($venta->pago?->metodo_pago ?? '-')); ?></td>
                        <td style="padding:10px 16px" class="text-end fw-semibold text-success">
                            $<?php echo e(number_format($venta->total, 2)); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No hay ventas hoy</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <?php if($totalPedidos > 0): ?>
                <tfoot style="background:#f8f9fa">
                    <tr>
                        <td colspan="4" class="text-end fw-bold" style="padding:10px 16px">TOTAL</td>
                        <td class="text-end fw-bold text-success" style="padding:10px 16px">
                            $<?php echo e(number_format($totalDia, 2)); ?>

                        </td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>
</div>



<div class="modal fade no-print" id="modalAbrirCaja" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('admin.caja.abrir')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-unlock me-1"></i> Abrir Caja</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Monto inicial en caja *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control form-control-lg"
                                   name="monto_inicial" step="0.01" min="0"
                                   placeholder="0.00" required autofocus>
                        </div>
                        <div class="form-text">Dinero físico con el que se inicia el día.</div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label fw-semibold">Notas (opcional)</label>
                        <input type="text" class="form-control" name="notas"
                               placeholder="Ej: Turno mañana, cajero Juan...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-unlock me-1"></i> Abrir Caja
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade no-print" id="modalCerrarCaja" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('admin.caja.cerrar')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-lock me-1"></i> Cerrar Caja</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php if($apertura): ?>
                    <div class="alert alert-light py-2 mb-3" style="font-size:13px">
                        <strong>Monto inicial:</strong> $<?php echo e(number_format($apertura->monto_inicial, 2)); ?><br>
                        <strong>Ventas en efectivo:</strong> $<?php echo e(number_format($totalEfectivo, 2)); ?><br>
                        <strong>Debería haber:</strong> <span class="text-success fw-bold">$<?php echo e(number_format($totalEsperado ?? 0, 2)); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="mb-1">
                        <label class="form-label fw-semibold">Monto contado en caja *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control form-control-lg"
                                   name="monto_final" step="0.01" min="0"
                                   placeholder="0.00" required autofocus>
                        </div>
                        <div class="form-text">Cuenta el dinero físico actual y escríbelo aquí.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmarCierre()">
                    <i class="bi bi-lock me-1"></i> Cerrar Caja
                </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function cerrarOperaciones() {
    if (!confirm('¿Confirmas cerrar operaciones?\nTodas las mesas quedarán inactivas.')) return;

    fetch('<?php echo e(route("admin.mesas.cerrar.operaciones")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('Operaciones cerradas. Todas las mesas están inactivas.');
            location.reload();
        }
    });
}
function confirmarCierre() {
    const montoFinal    = parseFloat(document.querySelector('[name="monto_final"]').value);
    const montoEsperado = <?php echo e($totalEsperado ?? 0); ?>;

    if (isNaN(montoFinal)) {
        alert('Por favor ingresa el monto contado.');
        return;
    }

    const diferencia = montoFinal - montoEsperado;

    if (diferencia !== 0) {
        const tipo  = diferencia > 0 ? 'SOBRANTE' : 'FALTANTE';
        const monto = Math.abs(diferencia).toFixed(2);

        alert(`⚠️ No se puede cerrar la caja\n\n`
            + `Esperado:  $${montoEsperado.toFixed(2)}\n`
            + `Contado:   $${montoFinal.toFixed(2)}\n`
            + `${tipo}:   $${monto}\n\n`
            + `El monto contado debe ser exactamente $${montoEsperado.toFixed(2)}.`);
        return; // ← bloquea el cierre
    }

    document.querySelector('#modalCerrarCaja form').submit();
}
function abrirOperaciones() {
    if (!confirm('¿Confirmas abrir operaciones?\nTodas las mesas quedarán disponibles.')) return;

    fetch('<?php echo e(route("admin.mesas.abrir.operaciones")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('Operaciones abiertas. Todas las mesas están disponibles.');
            location.reload();
        }
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Ryzen Gaming\Documents\aaa\SISTEMA_RESTAURANTE\restaurante-laravel\resources\views/admin/cierre-caja.blade.php ENDPATH**/ ?>