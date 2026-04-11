@php
$rol = auth()->user()->rol;
@endphp

<nav class="col-md-2 d-none d-md-block sidebar pt-3">
    <div class="position-sticky">
        <ul class="nav flex-column">
            @if($rol === 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}" 
                       href="{{ route('admin.usuarios.index') }}">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.categorias.*') ? 'active' : '' }}" 
                       href="{{ route('admin.categorias.index') }}">
                        <i class="bi bi-tags"></i> Categorías
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}" 
                       href="{{ route('admin.productos.index') }}">
                        <i class="bi bi-box-seam"></i> Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.mesas.*') ? 'active' : '' }}" 
                       href="{{ route('admin.mesas.index') }}">
                        <i class="bi bi-grid-3x3"></i> Mesas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}" 
                       href="{{ route('admin.reportes.ventas') }}">
                        <i class="bi bi-graph-up"></i> Reportes
                    </a>
                </li>
                <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.facturas.*') ? 'active' : '' }}" 
       href="{{ route('admin.facturas.index') }}">
        <i class="bi bi-receipt"></i> Facturas
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.config.factura') ? 'active' : '' }}"
       href="{{ route('admin.config.factura') }}">
        <i class="bi bi-gear"></i> Config. Factura
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.cierre.caja') ? 'active' : '' }}" 
       href="{{ route('admin.cierre.caja') }}">
        <i class="bi bi-cash-stack"></i> Caja
    </a>
</li>

<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.backups.*') ? 'active' : '' }}"
       href="{{ route('admin.backups.index') }}">
        <i class="bi bi-database"></i> Backups
    </a>
</li>
            
            
                <li class="nav-item mt-3">
                    <div class="px-3 text-muted text-uppercase" style="font-size: 0.75rem;">
                        Operaciones
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}" 
                       href="{{ route('pos.index') }}">
                        <i class="bi bi-cart3"></i> Punto de Venta
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('cocina.*') ? 'active' : '' }}" 
                       href="{{ route('cocina.index') }}">
                        <i class="bi bi-fire"></i> Vista Cocina
                    </a>
                </li>
            @elseif($rol === 'recepcionista')
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('pos.*') ? 'active' : '' }}" 
           href="{{ route('pos.index') }}">
            <i class="bi bi-cart3"></i> Punto de Venta
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.mesas.*') ? 'active' : '' }}" 
           href="{{ route('admin.mesas.index') }}">
            <i class="bi bi-calendar-check"></i> Reservaciones
        </a>
    </li>
            @elseif($rol === 'cocina')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('cocina.*') ? 'active' : '' }}" 
                       href="{{ route('cocina.index') }}">
                        <i class="bi bi-fire"></i> Pedidos
                    </a>
                </li>
                
            @endif
        </ul>
    </div>
</nav>
