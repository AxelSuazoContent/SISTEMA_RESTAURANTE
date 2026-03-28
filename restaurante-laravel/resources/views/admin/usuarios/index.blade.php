@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-people"></i> Usuarios</h1>
    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
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
                    @forelse($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->nombre }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>
                            <span class="badge bg-{{ $usuario->rol === 'admin' ? 'danger' : ($usuario->rol === 'cocina' ? 'warning' : 'info') }}">
                                {{ ucfirst($usuario->rol) }}
                            </span>
                        </td>
                        <td>{{ $usuario->telefono ?: '-' }}</td>
                        <td>
                            <span class="badge bg-{{ $usuario->activo ? 'success' : 'secondary' }}">
                                {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if(auth()->id() !== $usuario->id)
                            <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="d-inline" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            No hay usuarios registrados
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($usuarios->hasPages())
    <div class="card-footer">
        {{ $usuarios->links() }}
    </div>
    @endif
</div>
@endsection
