<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;

    protected $table = 'detalles_pedido';

    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'notas',
        'estado',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
    ];

    /**
     * Estados posibles de un detalle
     */
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_PREPARANDO = 'preparando';
    const ESTADO_LISTO = 'listo';
    const ESTADO_ENTREGADO = 'entregado';

    /**
     * Relación con pedido
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    /**
     * Relación con producto
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * Calcular el subtotal
     */
    public function getSubtotalAttribute(): float
    {
        return round($this->precio_unitario * $this->cantidad, 2);
    }

    /**
     * Scope para detalles pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    /**
     * Scope para detalles en preparación
     */
    public function scopePreparando($query)
    {
        return $query->where('estado', self::ESTADO_PREPARANDO);
    }

    /**
     * Scope para detalles listos
     */
    public function scopeListos($query)
    {
        return $query->where('estado', self::ESTADO_LISTO);
    }

    /**
     * Obtener el estado formateado
     */
    public function getEstadoFormateadoAttribute(): string
    {
        $estados = [
            'pendiente' => 'Pendiente',
            'preparando' => 'En preparación',
            'listo' => 'Listo',
            'entregado' => 'Entregado',
        ];

        return $estados[$this->estado] ?? $this->estado;
    }

    /**
     * Obtener el color del estado
     */
    public function getEstadoColorAttribute(): string
    {
        $colores = [
            'pendiente' => 'warning',
            'preparando' => 'info',
            'listo' => 'success',
            'entregado' => 'primary',
        ];

        return $colores[$this->estado] ?? 'secondary';
    }
}
