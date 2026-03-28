<?php $__env->startSection('title', 'Configuración de Factura'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-gear"></i> Configuración de Factura SAR</h1>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card" style="border-radius:12px;border:1px solid #e9ecef">
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.config.factura.update')); ?>">
                    <?php echo csrf_field(); ?>

                    <h6 class="fw-semibold mb-3 text-muted text-uppercase" style="font-size:11px;letter-spacing:.05em">
                        Datos del Negocio
                    </h6>

                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label">Nombre del Negocio *</label>
                            <input type="text" class="form-control" name="nombre_negocio"
                                   value="<?php echo e(old('nombre_negocio', $config->nombre_negocio)); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">RTN *</label>
                            <input type="text" class="form-control" name="rtn"
                                   value="<?php echo e(old('rtn', $config->rtn)); ?>" required
                                   placeholder="08011999123456">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Dirección *</label>
                            <input type="text" class="form-control" name="direccion"
                                   value="<?php echo e(old('direccion', $config->direccion)); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Teléfono *</label>
                            <input type="text" class="form-control" name="telefono"
                                   value="<?php echo e(old('telefono', $config->telefono)); ?>" required>
                        </div>
                    </div>

                    <hr class="my-3">

                    <h6 class="fw-semibold mb-3 text-muted text-uppercase" style="font-size:11px;letter-spacing:.05em">
                        Datos SAR
                    </h6>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">CAI (Código de Autorización de Impresión) *</label>
                            <input type="text" class="form-control font-monospace" name="cai"
                                   value="<?php echo e(old('cai', $config->cai)); ?>" required
                                   placeholder="A1B2C3-D4E5F6-G7H8I9-J0K1L2-M3N4O5-P6">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Rango Desde *</label>
                            <input type="text" class="form-control font-monospace" name="rango_desde"
                                   value="<?php echo e(old('rango_desde', $config->rango_desde)); ?>" required
                                   placeholder="001-001-01-00000001">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Rango Hasta *</label>
                            <input type="text" class="form-control font-monospace" name="rango_hasta"
                                   value="<?php echo e(old('rango_hasta', $config->rango_hasta)); ?>" required
                                   placeholder="001-001-01-00099999">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Fecha Límite *</label>
                            <input type="date" class="form-control" name="fecha_limite_emision"
                                   value="<?php echo e(old('fecha_limite_emision', $config->fecha_limite_emision->format('Y-m-d'))); ?>" required>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Guardar Configuración
                        </button>
                    </div>

                </form>
            </div>
        </div>

        
        <div class="card mt-3" style="border-radius:12px;border:1px solid #e9ecef">
            <div class="card-body">
                <h6 class="fw-semibold mb-3"><i class="bi bi-eye me-1"></i> Preview en Factura</h6>
                <div style="font-family:'Courier New',monospace;font-size:12px;background:#f8f9fa;padding:16px;border-radius:8px">
                    <div style="text-align:center;border-bottom:1px dashed #999;padding-bottom:10px;margin-bottom:10px">
                        <strong style="font-size:14px"><?php echo e($config->nombre_negocio); ?></strong><br>
                        RTN: <?php echo e($config->rtn); ?><br>
                        <?php echo e($config->direccion); ?><br>
                        Tel: <?php echo e($config->telefono); ?>

                    </div>
                    <div style="font-size:11px;color:#555">
                        CAI: <?php echo e($config->cai); ?><br>
                        Rango: <?php echo e($config->rango_desde); ?> al <?php echo e($config->rango_hasta); ?><br>
                        Fecha límite emisión: <?php echo e($config->fecha_limite_emision->format('d/m/Y')); ?>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Ryzen Gaming\Documents\SISTEMA DE RESTAURANTE\restaurante-laravel\resources\views/admin/config-factura.blade.php ENDPATH**/ ?>