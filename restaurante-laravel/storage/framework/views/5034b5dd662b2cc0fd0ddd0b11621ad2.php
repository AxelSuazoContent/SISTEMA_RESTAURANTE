<?php
$rol = auth()->user()->rol;
?>

<nav class="col-md-2 d-none d-md-block sidebar pt-3">
    <div class="position-sticky">
        <ul class="nav flex-column">
            <?php if($rol === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('admin.dashboard')); ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('admin.usuarios.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('admin.usuarios.index')); ?>">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('admin.categorias.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('admin.categorias.index')); ?>">
                        <i class="bi bi-tags"></i> Categorías
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('admin.productos.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('admin.productos.index')); ?>">
                        <i class="bi bi-box-seam"></i> Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('admin.mesas.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('admin.mesas.index')); ?>">
                        <i class="bi bi-grid-3x3"></i> Mesas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('admin.reportes.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('admin.reportes.ventas')); ?>">
                        <i class="bi bi-graph-up"></i> Reportes
                    </a>
                </li>
                <li class="nav-item">
    <a class="nav-link <?php echo e(request()->routeIs('admin.facturas.*') ? 'active' : ''); ?>" 
       href="<?php echo e(route('admin.facturas.index')); ?>">
        <i class="bi bi-receipt"></i> Facturas
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?php echo e(request()->routeIs('admin.config.factura') ? 'active' : ''); ?>"
       href="<?php echo e(route('admin.config.factura')); ?>">
        <i class="bi bi-gear"></i> Config. Factura
    </a>
</li>
<li class="nav-item">
    <a class="nav-link <?php echo e(request()->routeIs('admin.cierre.caja') ? 'active' : ''); ?>" 
       href="<?php echo e(route('admin.cierre.caja')); ?>">
        <i class="bi bi-cash-stack"></i> Cierre de Caja
    </a>
</li>
            
            
                <li class="nav-item mt-3">
                    <div class="px-3 text-muted text-uppercase" style="font-size: 0.75rem;">
                        Operaciones
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('pos.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('pos.index')); ?>">
                        <i class="bi bi-cart3"></i> Punto de Venta
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('cocina.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('cocina.index')); ?>">
                        <i class="bi bi-fire"></i> Vista Cocina
                    </a>
                </li>
            <?php elseif($rol === 'recepcionista'): ?>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('pos.*') ? 'active' : ''); ?>" 
           href="<?php echo e(route('pos.index')); ?>">
            <i class="bi bi-cart3"></i> Punto de Venta
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo e(request()->routeIs('admin.mesas.*') ? 'active' : ''); ?>" 
           href="<?php echo e(route('admin.mesas.index')); ?>">
            <i class="bi bi-calendar-check"></i> Reservaciones
        </a>
    </li>
            <?php elseif($rol === 'cocina'): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(request()->routeIs('cocina.*') ? 'active' : ''); ?>" 
                       href="<?php echo e(route('cocina.index')); ?>">
                        <i class="bi bi-fire"></i> Pedidos
                    </a>
                </li>
                
            <?php endif; ?>
        </ul>
    </div>
</nav>
<?php /**PATH C:\Users\Ryzen Gaming\Documents\SISTEMA DE RESTAURANTE\restaurante-laravel\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>