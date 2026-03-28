<?php $__env->startSection('title', 'Facturas'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-receipt"></i> Facturas</h1>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8f9fa">
                    <tr>
                        <th style="padding:10px 16px">N° Factura</th>
                        <th style="padding:10px 16px">Fecha</th>
                        <th style="padding:10px 16px">Cliente / Mesa</th>
                        <th style="padding:10px 16px">Método</th>
                        <th style="padding:10px 16px">Atendió</th>
                        <th style="padding:10px 16px" class="text-end">Total</th>
                        <th style="padding:10px 16px"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $facturas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $factura): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="padding:12px 16px">
                            <strong class="text-primary"><?php echo e($factura->numero_factura); ?></strong>
                        </td>
                        <td style="padding:12px 16px"><?php echo e($factura->created_at->format('d/m/Y H:i')); ?></td>
                        <td style="padding:12px 16px"><?php echo e($factura->cliente_nombre ?: '-'); ?></td>
                        <td style="padding:12px 16px"><?php echo e(ucfirst($factura->metodo_pago)); ?></td>
                        <td style="padding:12px 16px"><?php echo e($factura->usuario->nombre); ?></td>
                        <td style="padding:12px 16px" class="text-end fw-semibold text-success">
                            $<?php echo e(number_format($factura->total, 2)); ?>

                        </td>
                        <td style="padding:12px 16px">
                            <a href="<?php echo e(route('admin.facturas.imprimir', $factura)); ?>"
                               target="_blank"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-printer"></i> Imprimir
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No hay facturas generadas</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($facturas->hasPages()): ?>
    <div class="card-footer">
        <?php echo e($facturas->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Ryzen Gaming\Documents\SISTEMA DE RESTAURANTE\restaurante-laravel\resources\views/admin/facturas/index.blade.php ENDPATH**/ ?>