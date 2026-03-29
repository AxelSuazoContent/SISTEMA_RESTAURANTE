<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'pedido_id',
        'metodo_pago',
        'monto',
        'cambio',
        'referencia',
        'notas',
        'cliente_rtn',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'cambio' => 'decimal:2',
    ];

    /**
     * Métodos de pago
     */
    const METODO_EFECTIVO = 'efectivo';
    const METODO_TARJETA = 'tarjeta';
    const METODO_TRANSFERENCIA = 'transferencia';
    const METODO_OTRO = 'otro';

    /**
     * Relación con pedido
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    /**
     * Obtener el método de pago formateado
     */
    public function getMetodoPagoFormateadoAttribute(): string
    {
        $metodos = [
            'efectivo' => 'Efectivo',
            'tarjeta' => 'Tarjeta',
            'transferencia' => 'Transferencia',
            'otro' => 'Otro',
        ];

        return $metodos[$this->metodo_pago] ?? $this->metodo_pago;
    }
}
