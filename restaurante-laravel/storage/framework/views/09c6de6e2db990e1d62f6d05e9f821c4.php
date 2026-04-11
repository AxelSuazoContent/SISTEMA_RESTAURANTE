<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Sistema Restaurante'); ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --info-color: #3498db;
        }

        body {
            background-color: #f5f6fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: var(--primary-color) !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 600;
            color: white !important;
        }

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
        }

        .nav-link:hover {
            color: white !important;
        }

        .sidebar {
            background-color: white;
            min-height: calc(100vh - 56px);
            box-shadow: 2px 0 4px rgba(0,0,0,0.05);
        }

        .sidebar .nav-link {
            color: #555 !important;
            padding: 12px 20px;
            border-radius: 0;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #f8f9fa;
            color: var(--primary-color) !important;
            border-left-color: var(--primary-color);
        }

        .sidebar .nav-link i {
            width: 24px;
            margin-right: 8px;
        }

        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            font-weight: 600;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
        }

        .btn-info {
            background-color: var(--info-color);
            border-color: var(--info-color);
            color: white;
        }

        .table {
            background-color: white;
        }

        .badge-estado {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.85em;
            font-weight: 500;
        }

        .estado-pendiente { background-color: #fff3cd; color: #856404; }
        .estado-preparando { background-color: #d1ecf1; color: #0c5460; }
        .estado-listo { background-color: #d4edda; color: #155724; }
        .estado-entregado { background-color: #cce5ff; color: #004085; }
        .estado-cancelado { background-color: #f8d7da; color: #721c24; }
        .estado-pagado { background-color: #d6d8db; color: #383d41; }

        .estado-disponible { background-color: #d4edda; color: #155724; }
        .estado-ocupada { background-color: #f8d7da; color: #721c24; }
        .estado-reservada { background-color: #fff3cd; color: #856404; }
        .estado-limpieza { background-color: #d1ecf1; color: #0c5460; }

        .stat-card {
            padding: 20px;
            border-radius: 8px;
            color: white;
        }

        .stat-card i {
            font-size: 2.5rem;
            opacity: 0.8;
        }

        .stat-card .number {
            font-size: 2rem;
            font-weight: 700;
        }

        .stat-card .label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .bg-primary-soft { background-color: #3498db; }
        .bg-success-soft { background-color: #27ae60; }
        .bg-warning-soft { background-color: #f39c12; }
        .bg-danger-soft { background-color: #e74c3c; }
        .bg-info-soft { background-color: #17a2b8; }

        .producto-card {
            cursor: pointer;
            transition: all 0.2s;
        }

        .producto-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .mesa-card {
            cursor: pointer;
            transition: all 0.2s;
        }

        .mesa-card:hover {
            transform: scale(1.02);
        }

        .pedido-cocina {
            border-left: 4px solid var(--warning-color);
        }

        .pedido-cocina.preparando {
            border-left-color: var(--info-color);
        }

        .pedido-cocina.listo {
            border-left-color: var(--success-color);
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
    
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>
    <?php if(auth()->guard()->check()): ?>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo e(route('dashboard')); ?>">
                <i class="bi bi-shop"></i> Sistema Restaurante
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if(auth()->user()->esAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('admin.dashboard')); ?>">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if(auth()->user()->esRecepcionista() || auth()->user()->esAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('pos.index')); ?>">
                                <i class="bi bi-cart3"></i> POS
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if(auth()->user()->esCocinero() || auth()->user()->esAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('cocina.index')); ?>">
                                <i class="bi bi-fire"></i> Cocina
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?php echo e(auth()->user()->nombre); ?>

                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('password.change')); ?>">
                                    <i class="bi bi-key"></i> Cambiar Contraseña
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <div class="container-fluid">
        <div class="row">
            <?php if(auth()->guard()->check()): ?>
                <?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>
            
            <main class="col-md-<?php echo e(auth()->check() ? '10' : '12'); ?> ms-sm-auto col-lg-<?php echo e(auth()->check() ? '10' : '12'); ?> px-md-4 py-4">
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\Ryzen Gaming\Documents\aaa\SISTEMA_RESTAURANTE\restaurante-laravel\resources\views/layouts/app.blade.php ENDPATH**/ ?>