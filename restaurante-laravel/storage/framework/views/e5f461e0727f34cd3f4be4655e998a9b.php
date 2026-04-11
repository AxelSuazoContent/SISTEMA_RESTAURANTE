

<?php $__env->startSection('title', 'Backups'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-database"></i> Respaldo de Base de Datos</h1>
    <form action="<?php echo e(route('admin.backups.crear')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-database-add"></i> Crear Backup Ahora
        </button>
    </form>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>
<?php if(session('error')): ?>
    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
<?php endif; ?>


<div class="card mb-4">
    <div class="card-header fw-semibold"><i class="bi bi-arrow-counterclockwise"></i> Restaurar Backup</div>
    <div class="card-body">
        <p class="text-muted small">Se hará un backup automático antes de restaurar para evitar pérdida de datos.</p>
        <form action="<?php echo e(route('admin.backups.restaurar')); ?>" method="POST" enctype="multipart/form-data" class="d-flex gap-2 align-items-center">
            <?php echo csrf_field(); ?>
            <input type="file" name="backup" accept=".sqlite" class="form-control" style="max-width:350px" required>
            <button type="submit" class="btn btn-warning" onclick="return confirm('¿Estás seguro? Se reemplazará la base de datos actual.')">
                <i class="bi bi-upload"></i> Restaurar
            </button>
        </form>
    </div>
</div>


<div class="card">
    <div class="card-header fw-semibold"><i class="bi bi-clock-history"></i> Backups Disponibles</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Archivo</th>
                        <th>Fecha</th>
                        <th>Tamaño</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $archivos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $archivo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><i class="bi bi-database text-primary"></i> <?php echo e($archivo['nombre']); ?></td>
                        <td><?php echo e($archivo['fecha']); ?></td>
                        <td><?php echo e($archivo['tamaño']); ?></td>
                        <td class="d-flex gap-2">
                            <a href="<?php echo e(route('admin.backups.descargar', $archivo['nombre'])); ?>" class="btn btn-sm btn-success">
                                <i class="bi bi-download"></i> Descargar
                            </a>
                            <form action="<?php echo e(route('admin.backups.eliminar', $archivo['nombre'])); ?>" method="POST"
                                  onsubmit="return confirm('¿Eliminar este backup?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            No hay backups disponibles
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Ryzen Gaming\Documents\aaa\SISTEMA_RESTAURANTE\restaurante-laravel\resources\views/admin/backups/index.blade.php ENDPATH**/ ?>