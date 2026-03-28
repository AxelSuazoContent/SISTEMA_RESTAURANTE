

<?php $__env->startSection('title', 'Usuarios'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-people"></i> Usuarios</h1>
    <a href="<?php echo e(route('admin.usuarios.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nuevo Usuario
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $usuarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $usuario): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($usuario->nombre); ?></td>
                        <td><?php echo e($usuario->email); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($usuario->rol === 'admin' ? 'danger' : ($usuario->rol === 'cocina' ? 'warning' : 'info')); ?>">
                                <?php echo e(ucfirst($usuario->rol)); ?>

                            </span>
                        </td>
                        <td><?php echo e($usuario->telefono ?: '-'); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($usuario->activo ? 'success' : 'secondary'); ?>">
                                <?php echo e($usuario->activo ? 'Activo' : 'Inactivo'); ?>

                            </span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('admin.usuarios.edit', $usuario)); ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php if(auth()->id() !== $usuario->id): ?>
                            <form action="<?php echo e(route('admin.usuarios.destroy', $usuario)); ?>" method="POST" class="d-inline" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No hay usuarios registrados
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($usuarios->hasPages()): ?>
    <div class="card-footer">
        <?php echo e($usuarios->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Ryzen Gaming\Documents\aaa\SISTEMA_RESTAURANTE\restaurante-laravel\resources\views/admin/usuarios/index.blade.php ENDPATH**/ ?>