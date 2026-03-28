@extends('layouts.app')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-key"></i> Cambiar Contraseña
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('password.change') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="password_actual" class="form-label">Contraseña Actual</label>
                        <input type="password" 
                               class="form-control @error('password_actual') is-invalid @enderror" 
                               id="password_actual" 
                               name="password_actual" 
                               required>
                        @error('password_actual')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nueva Contraseña</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required
                               minlength="6">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" 
                               class="form-control" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required
                               minlength="6">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Cambiar Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
