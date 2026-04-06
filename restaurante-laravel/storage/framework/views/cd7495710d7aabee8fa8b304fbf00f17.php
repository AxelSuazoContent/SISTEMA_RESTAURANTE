

<?php $__env->startSection('title', 'Categorías'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-tags"></i> Categorías</h1>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrear">
        <i class="bi bi-plus-lg"></i> Nueva Categoría
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Color</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($categoria->nombre); ?></td>
                        <td><?php echo e(Str::limit($categoria->descripcion, 50)); ?></td>
                        <td>
                            <span class="badge" style="background-color: <?php echo e($categoria->color); ?>">
                                <?php echo e($categoria->color); ?>

                            </span>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo e($categoria->activo ? 'success' : 'secondary'); ?>">
                                <?php echo e($categoria->activo ? 'Activa' : 'Inactiva'); ?>

                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modalEditar<?php echo e($categoria->id); ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="<?php echo e(route('admin.categorias.destroy', $categoria)); ?>" method="POST" class="d-inline"
                                  onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?')">
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
                        <td colspan="5" class="text-center text-muted py-4">
                            No hay categorías registradas
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($categorias->hasPages()): ?>
    <div class="card-footer">
        <?php echo e($categorias->links()); ?>

    </div>
    <?php endif; ?>
</div>


<div class="modal fade" id="modalCrear" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('admin.categorias.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="color" class="form-label">Color</label>
                        <input type="color" class="form-control form-control-color"
                               id="color" name="color" value="#6c757d">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="modal fade" id="modalEditar<?php echo e($categoria->id); ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('admin.categorias.update', $categoria)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Editar Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre<?php echo e($categoria->id); ?>" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="nombre<?php echo e($categoria->id); ?>"
                               name="nombre" value="<?php echo e($categoria->nombre); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion<?php echo e($categoria->id); ?>" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion<?php echo e($categoria->id); ?>"
                                  name="descripcion" rows="2"><?php echo e($categoria->descripcion); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="color<?php echo e($categoria->id); ?>" class="form-label">Color</label>
                        <input type="color" class="form-control form-control-color"
                               id="color<?php echo e($categoria->id); ?>"
                               name="color" value="<?php echo e($categoria->color); ?>">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="activo<?php echo e($categoria->id); ?>"
                               name="activo" value="1" <?php echo e($categoria->activo ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="activo<?php echo e($categoria->id); ?>">Activa</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Ryzen Gaming\Documents\aaa\SISTEMA_RESTAURANTE\restaurante-laravel\resources\views/admin/categorias/index.blade.php ENDPATH**/ ?>