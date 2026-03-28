<?php $__env->startSection('title', 'Productos'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-box-seam"></i> Productos</h1>
    <a href="<?php echo e(route('admin.productos.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Nuevo Producto
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $productos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $producto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            
                            <?php if($producto->imagen): ?>
                                <img src="<?php echo e(asset('storage/' . $producto->imagen)); ?>" 
                                     alt="<?php echo e($producto->nombre); ?>" 
                                     class="img-thumbnail" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px; border-radius: 4px;">
                                    <i class="bi bi-image"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($producto->nombre); ?></td>
                        <td>
                            <span class="badge" style="background-color: <?php echo e($producto->categoria->color); ?>">
                                <?php echo e($producto->categoria->nombre); ?>

                            </span>
                        </td>
                        <td>$<?php echo e(number_format($producto->precio, 2)); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($producto->stock < 10 ? 'danger' : ($producto->stock < 20 ? 'warning' : 'success')); ?>">
                                <?php echo e($producto->stock); ?>

                            </span>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo e($producto->activo ? 'success' : 'secondary'); ?>">
                                <?php echo e($producto->activo ? 'Activo' : 'Inactivo'); ?>

                            </span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('admin.productos.edit', $producto)); ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?php echo e(route('admin.productos.destroy', $producto)); ?>" method="POST" class="d-inline" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
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
                        <td colspan="7" class="text-center text-muted py-4">
                            No hay productos registrados
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($productos->hasPages()): ?>
    <div class="card-footer">
        <?php echo e($productos->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Ryzen Gaming\Documents\SISTEMA DE RESTAURANTE\restaurante-laravel\resources\views/admin/productos/index.blade.php ENDPATH**/ ?>