<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activación del Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #1a1a2e; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { border-radius: 16px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .logo { font-size: 48px; color: #2D6A4F; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card p-4">
                    <div class="text-center mb-4">
                        <div class="logo"><i class="bi bi-shield-lock"></i></div>
                        <h4 class="fw-bold mt-2">Activación del Sistema</h4>
                        <p class="text-muted small">Ingresa tu clave de licencia para continuar</p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if($info && $info['valida'])
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i> Sistema activado
                            <br>
                            <small>Licencia <strong>{{ $info['tipo'] }}</strong> — Expira: {{ $info['expiracion'] }} ({{ $info['dias'] }} días restantes)</small>
                        </div>
                        <a href="{{ route('dashboard') }}" class="btn btn-success w-100">
                            <i class="bi bi-arrow-right"></i> Ir al Sistema
                        </a>
                    @else
                        <form action="{{ route('licencia.activar') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Clave de Licencia</label>
                                <input type="text" name="clave" class="form-control font-monospace text-center"
                                       placeholder="BASIC-2029-12-31-ABCD1234-EFGH5678"
                                       style="letter-spacing: 1px;" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-unlock"></i> Activar Sistema
                            </button>
                        </form>
                        <div class="text-center mt-3">
                            <small class="text-muted">¿No tienes una licencia? Contacta al proveedor.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>