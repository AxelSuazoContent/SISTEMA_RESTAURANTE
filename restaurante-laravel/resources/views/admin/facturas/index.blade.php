@extends('layouts.app')

@section('title', 'Facturas')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-receipt"></i> Facturas</h1>
</div>
 
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8f9fa">
                    <tr>
                        <th style="padding:10px 16px">N° Factura</th>
                        <th style="padding:10px 16px">Fecha</th>
                        <th style="padding:10px 16px">Cliente / Mesa</th>
                        <th style="padding:10px 16px">Método</th>
                        <th style="padding:10px 16px">Atendió</th>
                        <th style="padding:10px 16px" class="text-end">Total</th>
                        <th style="padding:10px 16px"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facturas as $factura)
                    <tr>
                        <td style="padding:12px 16px">
                            <strong class="text-primary">{{ $factura->numero_factura }}</strong>
                        </td>
                        <td style="padding:12px 16px">{{ $factura->created_at->format('d/m/Y H:i') }}</td>
                        <td style="padding:12px 16px">{{ $factura->cliente_nombre ?: '-' }}</td>
                        <td style="padding:12px 16px">{{ ucfirst($factura->metodo_pago) }}</td>
                        <td style="padding:12px 16px">{{ $factura->usuario->nombre }}</td>
                        <td style="padding:12px 16px" class="text-end fw-semibold text-success">
                            ${{ number_format($factura->total, 2) }}
                        </td>
                        <td style="padding:12px 16px">
                            <a href="{{ route('admin.facturas.imprimir', $factura) }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-printer"></i> Imprimir
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No hay facturas generadas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($facturas->hasPages())
    <div class="card-footer">
        {{ $facturas->links() }}
    </div>
    @endif
</div>
@endsection