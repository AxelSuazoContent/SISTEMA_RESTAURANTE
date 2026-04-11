<?php $__env->startSection('title', 'Reporte de Ventas'); ?>

<?php $__env->startSection('styles'); ?>
<style>

@media print {
    .no-print { display: none !important; }
    .sidebar { display: none !important; }
    .navbar { display: none !important; }
    main.col-md-10 {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .col-md-10.ms-sm-auto {
        margin-left: 0 !important;
    }
}
.stat-label { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; opacity: 0.8; }
.stat-value { font-size: 28px; font-weight: 700; line-height: 1.1; margin-top: 4px; }
.stat-sub   { font-size: 12px; opacity: 0.75; margin-top: 4px; }
.chart-card { border: 1px solid #e9ecef; border-radius: 12px; box-shadow: 0 1px 6px rgba(0,0,0,.04); }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2 mb-0"><i class="bi bi-graph-up"></i> Reporte de Ventas</h1>
        <small class="text-muted">
            <?php echo e($fechaInicio->format('d/m/Y')); ?> — <?php echo e($fechaFin->format('d/m/Y')); ?>

        </small>
    </div>
    <div class="d-flex gap-2 no-print">
        <a href="<?php echo e(route('admin.reportes.ventas.export', request()->query())); ?>" class="btn btn-success">
            <i class="bi bi-file-earmark-excel"></i> Exportar Excel
        </a>
        <button onclick="window.print()" class="btn btn-outline-primary">
            <i class="bi bi-printer"></i> Imprimir / PDF
        </button>
    </div>
</div>


<div class="card mb-4 no-print" style="border-radius:12px;border:1px solid #e9ecef">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.reportes.ventas')); ?>" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Fecha Inicio</label>
                <input type="date" class="form-control" name="fecha_inicio"
                       value="<?php echo e($fechaInicio->format('Y-m-d')); ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Fecha Fin</label>
                <input type="date" class="form-control" name="fecha_fin"
                       value="<?php echo e($fechaFin->format('Y-m-d')); ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Filtrar
                </button>
            </div>
        </form>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card bg-primary text-white">
            <div class="stat-label">Total Ventas</div>
            <div class="stat-value">$<?php echo e(number_format($totalVentas, 0)); ?></div>
            <div class="stat-sub">en el período</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card bg-success text-white">
            <div class="stat-label">Pedidos Pagados</div>
            <div class="stat-value"><?php echo e($totalPedidos); ?></div>
            <div class="stat-sub">pedidos completados</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card bg-warning text-dark">
            <div class="stat-label">Ticket Promedio</div>
            <div class="stat-value">$<?php echo e(number_format($ticketPromedio, 0)); ?></div>
            <div class="stat-sub">por pedido</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card bg-info text-white">
            <div class="stat-label">Días con ventas</div>
            <div class="stat-value"><?php echo e($ventasPorDia->count()); ?></div>
            <div class="stat-sub">días activos</div>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">

    
    <div class="col-lg-8">
        <div class="chart-card p-3 h-100">
            <h6 class="fw-semibold mb-3"><i class="bi bi-bar-chart-line me-1"></i> Ventas por Día</h6>
            <canvas id="graficaDias" height="100"></canvas>
        </div>
    </div>

    
    <div class="col-lg-4">
        <div class="chart-card p-3 h-100">
            <h6 class="fw-semibold mb-3"><i class="bi bi-pie-chart me-1"></i> Método de Pago</h6>
            <canvas id="graficaMetodos" height="200"></canvas>
        </div>
    </div>

    
    <div class="col-12">
        <div class="chart-card p-3">
            <h6 class="fw-semibold mb-3"><i class="bi bi-trophy me-1"></i> Top 5 Productos Más Vendidos</h6>
            <canvas id="graficaProductos" height="60"></canvas>
        </div>
    </div>

</div>


<div class="chart-card mb-4">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0"><i class="bi bi-list me-1"></i> Detalle de Ventas</h6>
        <small class="text-muted no-print"><?php echo e($ventas->total()); ?> registros</small>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:13px">
            <thead style="background:#f8f9fa">
                <tr>
                    <th style="padding:10px 16px"># Pedido</th>
                    <th style="padding:10px 16px">Fecha</th>
                    <th style="padding:10px 16px">Mesa / Cliente</th>
                    <th style="padding:10px 16px">Atendió</th>
                    <th style="padding:10px 16px">Método</th>
                    <th style="padding:10px 16px" class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $ventas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="padding:10px 16px" class="text-muted">#<?php echo e($venta->id); ?></td>
                    <td style="padding:10px 16px"><?php echo e($venta->created_at->format('d/m/Y H:i')); ?></td>
                    <td style="padding:10px 16px">
                        <?php if($venta->mesa): ?> Mesa <?php echo e($venta->mesa->numero); ?>

                        <?php else: ?> <?php echo e($venta->cliente_nombre ?: 'Sin nombre'); ?>

                        <?php endif; ?>
                    </td>
                    <td style="padding:10px 16px"><?php echo e($venta->usuario->nombre); ?></td>
                    <td style="padding:10px 16px">
                        <?php if($venta->pago): ?> <?php echo e($venta->pago->metodo_pago_formateado); ?>

                        <?php else: ?> -
                        <?php endif; ?>
                    </td>
                    <td style="padding:10px 16px" class="text-end fw-semibold text-success">
                        $<?php echo e(number_format($venta->total, 2)); ?>

                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No hay ventas en el período seleccionado
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
            <?php if($totalPedidos > 0): ?>
            <tfoot style="background:#f8f9fa">
                <tr>
                    <td colspan="5" class="text-end fw-bold" style="padding:10px 16px">TOTAL</td>
                    <td class="text-end fw-bold text-primary" style="padding:10px 16px">
                        $<?php echo e(number_format($totalVentas, 2)); ?>

                    </td>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
    <?php if($ventas->hasPages()): ?>
    <div class="p-3 no-print">
        <?php echo e($ventas->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const diasLabels   = <?php echo json_encode($ventasPorDia->pluck('fecha'), 15, 512) ?>;
const diasTotales  = <?php echo json_encode($ventasPorDia->pluck('total')->map(fn($v) => round($v, 2)), 512) ?>;
const metodosLabels  = <?php echo json_encode($ventasPorMetodo->pluck('metodo_pago'), 15, 512) ?>;
const metodosTotales = <?php echo json_encode($ventasPorMetodo->pluck('total'), 15, 512) ?>;
const prodLabels   = <?php echo json_encode($topProductos->pluck('nombre'), 15, 512) ?>;
const prodTotales  = <?php echo json_encode($topProductos->pluck('total_vendido'), 15, 512) ?>;

const coloresPago = ['#0d6efd','#198754','#ffc107','#dc3545','#6c757d'];

new Chart(document.getElementById('graficaDias'), {
    type: 'bar',
    data: {
        labels: diasLabels,
        datasets: [{ label: 'Ventas ($)', data: diasTotales,
            backgroundColor: 'rgba(13,110,253,0.15)', borderColor: '#0d6efd',
            borderWidth: 2, borderRadius: 6 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { callback: v => '$' + v.toLocaleString() } } } }
});

new Chart(document.getElementById('graficaMetodos'), {
    type: 'doughnut',
    data: { labels: metodosLabels,
        datasets: [{ data: metodosTotales, backgroundColor: coloresPago, borderWidth: 2 }] },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } }, cutout: '65%' }
});

new Chart(document.getElementById('graficaProductos'), {
    type: 'bar',
    data: { labels: prodLabels,
        datasets: [{ label: 'Unidades vendidas', data: prodTotales,
            backgroundColor: coloresPago, borderRadius: 6 }] },
    options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } },
        scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Ryzen Gaming\Documents\aaa\SISTEMA_RESTAURANTE\restaurante-laravel\resources\views/admin/reportes/ventas.blade.php ENDPATH**/ ?>