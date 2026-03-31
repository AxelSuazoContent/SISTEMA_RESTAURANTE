@extends('layouts.app')

@section('title', 'Categorías')

@section('content')
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
                        <th>Orden</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Color</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $categoria)
                    <tr>
                        <td>{{ $categoria->orden }}</td>
                        <td>{{ $categoria->nombre }}</td>
                        <td>{{ Str::limit($categoria->descripcion, 50) }}</td>
                        <td>
                            <span class="badge" style="background-color: {{ $categoria->color }}">
                                {{ $categoria->color }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $categoria->activo ? 'success' : 'secondary' }}">
                                {{ $categoria->activo ? 'Activa' : 'Inactiva' }}
                            </span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                    data-bs-target="#modalEditar{{ $categoria->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form action="{{ route('admin.categorias.destroy', $categoria) }}" method="POST" class="d-inline" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar esta categoría?')">
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
                        <td colspan="6" class="text-center text-muted py-4">
                            No hay categorías registradas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($categorias->hasPages())
    <div class="card-footer">
        {{ $categorias->links() }}
    </div>
    @endif
</div>

<!-- Modal Crear -->
<div class="modal fade" id="modalCrear" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.categorias.store') }}">
                @csrf
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
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="color" class="form-label">Color</label>
                            <input type="color" class="form-control" id="color" name="color" value="#6c757d">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="orden" class="form-label">Orden</label>
                            <input type="number" class="form-control" id="orden" name="orden" value="0" min="0">
                        </div>
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

<!-- Modales Editar -->
@foreach($categorias as $categoria)
<div class="modal fade" id="modalEditar{{ $categoria->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.categorias.update', $categoria) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Editar Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre{{ $categoria->id }}" class="form-label">Nombre *</label>
                        <input type="text" class="form-control" id="nombre{{ $categoria->id }}" 
                               name="nombre" value="{{ $categoria->nombre }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion{{ $categoria->id }}" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion{{ $categoria->id }}" 
                                  name="descripcion" rows="2">{{ $categoria->descripcion }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="color{{ $categoria->id }}" class="form-label">Color</label>
                            <input type="color" class="form-control" id="color{{ $categoria->id }}" 
                                   name="color" value="{{ $categoria->color }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="orden{{ $categoria->id }}" class="form-label">Orden</label>
                            <input type="number" class="form-control" id="orden{{ $categoria->id }}" 
       name="orden" value="{{ $categoria->orden }}" min="0">
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="activo{{ $categoria->id }}" 
                               name="activo" value="1" {{ $categoria->activo ? 'checked' : '' }}>
                        <label class="form-check-label" for="activo{{ $categoria->id }}">Activa</label>
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
@endforeach
@endsection
