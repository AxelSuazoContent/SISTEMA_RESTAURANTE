

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-speedometer2"></i> Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <span class="text-muted"><?php echo e(now()->format('d/m/Y H:i')); ?></span>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card bg-primary-soft">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="number">$<?php echo e(number_format($ventasHoy, 2)); ?></div>
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
                    <div class="number"><?php echo e($pedidosHoy); ?></div>
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
                    <div class="number"><?php echo e($pedidosActivos); ?></div>
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
                    <div class="number"><?php echo e($productosBajoStock); ?></div>
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
                <a href="<?php echo e(route('pos.index')); ?>" class="btn btn-sm btn-primary">
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
                            <?php $__empty_1 = true; $__currentLoopData = $pedidosRecientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pedido): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>#<?php echo e($pedido->id); ?></td>
                                <td>
                                    <?php if($pedido->mesa): ?>
                                        Mesa <?php echo e($pedido->mesa->numero); ?>

                                    <?php else: ?>
                                        <?php echo e($pedido->cliente_nombre ?: 'Sin nombre'); ?>

                                    <?php endif; ?>
                                </td>
                                <td>$<?php echo e(number_format($pedido->total, 2)); ?></td>
                                <td>
                                    <span class="badge-estado estado-<?php echo e($pedido->estado); ?>">
                                        <?php echo e($pedido->estado_formateado); ?>

                                    </span>
                                </td>
                                <td><?php echo e($pedido->tiempo_transcurrido); ?></td>
                                
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No hay pedidos recientes
                                </td>
                            </tr>
                            <?php endif; ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $productosTop; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?php echo e($producto->nombre); ?>

                        <span class="badge bg-primary rounded-pill"><?php echo e($producto->total_vendido); ?></span>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="list-group-item text-center text-muted py-4">
                        No hay datos disponibles
                    </li>
                    <?php endif; ?>
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
                    <a href="<?php echo e(route('admin.productos.create')); ?>" class="btn btn-outline-primary">
                        <i class="bi bi-plus-circle"></i> Nuevo Producto
                    </a>
                    <a href="<?php echo e(route('admin.usuarios.create')); ?>" class="btn btn-outline-success">
                        <i class="bi bi-person-plus"></i> Nuevo Usuario
                    </a>
                    <a href="<?php echo e(route('admin.reportes.ventas')); ?>" class="btn btn-outline-info">
                        <i class="bi bi-graph-up"></i> Reporte de Ventas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function verPedido(id) {
    // Aquí se puede implementar un modal para ver detalles del pedido
    window.open(`/pos/pedido/${id}`, '_blank');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Ryzen Gaming\Documents\aaa\SISTEMA_RESTAURANTE\restaurante-laravel\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>