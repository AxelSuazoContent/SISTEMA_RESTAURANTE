@extends('layouts.app')

@section('title', 'Productos')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-box-seam"></i> Productos</h1>
    <a href="{{ route('admin.productos.create') }}" class="btn btn-primary">
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
                    @forelse($productos as $producto)
                    <tr>
                        <td>
                            
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                     alt="{{ $producto->nombre }}" 
                                     class="img-thumbnail" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px; border-radius: 4px;">
                                    <i class="bi bi-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $producto->nombre }}</td>
                        <td>
                            <span class="badge" style="background-color: {{ $producto->categoria->color }}">
                                {{ $producto->categoria->nombre }}
                            </span>
                        </td>
                        <td>${{ number_format($producto->precio, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $producto->stock < 10 ? 'danger' : ($producto->stock < 20 ? 'warning' : 'success') }}">
                                {{ $producto->stock }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $producto->activo ? 'success' : 'secondary' }}">
                                {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.productos.edit', $producto) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.productos.destroy', $producto) }}" method="POST" class="d-inline" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar este producto?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            No hay productos registrados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($productos->hasPages())
    <div class="card-footer">
        {{ $productos->links() }}
    </div>
    @endif
</div>
@endsection
