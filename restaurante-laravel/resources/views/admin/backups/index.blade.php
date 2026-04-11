@extends('layouts.app')

@section('title', 'Backups')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-database"></i> Respaldo de Base de Datos</h1>
    <form action="{{ route('admin.backups.crear') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-database-add"></i> Crear Backup Ahora
        </button>
    </form>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- Restaurar --}}
<div class="card mb-4">
    <div class="card-header fw-semibold"><i class="bi bi-arrow-counterclockwise"></i> Restaurar Backup</div>
    <div class="card-body">
        <p class="text-muted small">Se hará un backup automático antes de restaurar para evitar pérdida de datos.</p>
        <form action="{{ route('admin.backups.restaurar') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2 align-items-center">
            @csrf
            <input type="file" name="backup" accept=".sqlite" class="form-control" style="max-width:350px" required>
            <button type="submit" class="btn btn-warning" onclick="return confirm('¿Estás seguro? Se reemplazará la base de datos actual.')">
                <i class="bi bi-upload"></i> Restaurar
            </button>
        </form>
    </div>
</div>

{{-- Lista de backups --}}
<div class="card">
    <div class="card-header fw-semibold"><i class="bi bi-clock-history"></i> Backups Disponibles</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Archivo</th>
                        <th>Fecha</th>
                        <th>Tamaño</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($archivos as $archivo)
                    <tr>
                        <td><i class="bi bi-database text-primary"></i> {{ $archivo['nombre'] }}</td>
                        <td>{{ $archivo['fecha'] }}</td>
                        <td>{{ $archivo['tamaño'] }}</td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('admin.backups.descargar', $archivo['nombre']) }}" class="btn btn-sm btn-success">
                                <i class="bi bi-download"></i> Descargar
                            </a>
                            <form action="{{ route('admin.backups.eliminar', $archivo['nombre']) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar este backup?')">
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
                        <td colspan="4" class="text-center text-muted py-4">
                            No hay backups disponibles
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection