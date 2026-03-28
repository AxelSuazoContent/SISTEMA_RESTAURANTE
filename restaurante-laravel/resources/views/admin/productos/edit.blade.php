@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-box-seam"></i> Editar Producto</h1>
    <a href="{{ route('admin.productos.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.productos.update', $producto) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" 
                                   class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" 
                                   name="nombre" 
                                   value="{{ old('nombre', $producto->nombre) }}" 
                                   required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="categoria_id" class="form-label">Categoría *</label>
                            <select class="form-select @error('categoria_id') is-invalid @enderror" 
                                    id="categoria_id" 
                                    name="categoria_id" 
                                    required>
                                <option value="">Selecciona</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" 
                                        {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categoria_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" 
                                  name="descripcion" 
                                  rows="2">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="precio" class="form-label">Precio de Venta *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       class="form-control @error('precio') is-invalid @enderror" 
                                       id="precio" 
                                       name="precio" 
                                       value="{{ old('precio', $producto->precio) }}" 
                                       step="0.01"
                                       min="0"
                                       required>
                            </div>
                            @error('precio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="costo" class="form-label">Costo</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       class="form-control @error('costo') is-invalid @enderror" 
                                       id="costo" 
                                       name="costo" 
                                       value="{{ old('costo', $producto->costo) }}" 
                                       step="0.01"
                                       min="0">
                            </div>
                            @error('costo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" 
                                   class="form-control @error('stock') is-invalid @enderror" 
                                   id="stock" 
                                   name="stock" 
                                   value="{{ old('stock', $producto->stock) }}" 
                                   min="0">
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="preparacion_minutos" class="form-label">Tiempo de Preparación (min)</label>
                            <input type="number" 
                                   class="form-control @error('preparacion_minutos') is-invalid @enderror" 
                                   id="preparacion_minutos" 
                                   name="preparacion_minutos" 
                                   value="{{ old('preparacion_minutos', $producto->preparacion_minutos) }}" 
                                   min="1">
                            @error('preparacion_minutos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            @if($producto->imagen)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $producto->imagen) }}" 
                                         alt="{{ $producto->nombre }}" 
                                         class="img-thumbnail" 
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                </div>
                            @endif
                            <input type="file" 
                                   class="form-control @error('imagen') is-invalid @enderror" 
                                   id="imagen" 
                                   name="imagen"
                                   accept="image/*">
                            <small class="text-muted">Deja en blanco para mantener la imagen actual</small>
                            @error('imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" id="activo" name="activo" value="1" 
                            {{ old('activo', $producto->activo) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activo">Producto Activo</label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Actualizar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
function previsualizarImagen(input) {
    const maxMB   = 1;
    const maxBytes = maxMB * 1024 * 1024;
    const allowed  = ['image/jpeg', 'image/png', 'image/webp'];
    const file     = input.files[0];

    if (!file) return;

    if (!allowed.includes(file.type)) {
        alert('Solo se permiten imágenes JPG, PNG o WebP.');
        input.value = '';
        return;
    }

    if (file.size > maxBytes) {
        alert(`La imagen es muy grande (${(file.size/1024/1024).toFixed(1)}MB). El máximo es ${maxMB}MB.`);
        input.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('preview').src = e.target.result;
        document.getElementById('previewContainer').classList.remove('d-none');
    };
    reader.readAsDataURL(file);
}
</script>
@endsection
