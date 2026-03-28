@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('styles')
<style>
    .login-wrapper {
        min-height: calc(100vh - 56px);
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<div class="login-wrapper">
    <div class="col-md-5 col-lg-4">
        <div class="card shadow">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="bi bi-shop" style="font-size: 3rem; color: #2c3e50;"></i>
                    <h3 class="mt-2">Sistema Restaurante</h3>
                    <p class="text-muted">Inicia sesión para continuar</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   placeholder="tu@email.com">
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   required
                                   placeholder="••••••••">
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Recordarme</label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i> Iniciar Sesión
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection