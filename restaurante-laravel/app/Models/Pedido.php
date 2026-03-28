<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'mesa_id',
        'usuario_id',
        'cliente_nombre',
        'cliente_telefono',
        'estado',
        'tipo',
        'total',
        'propina',
        'notas',
        'fecha_completado',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'propina' => 'decimal:2',
        'fecha_completado' => 'datetime',
    ];

    /**
     * Estados posibles de un pedido
     */
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_PREPARANDO = 'preparando';
    const ESTADO_LISTO = 'listo';
    const ESTADO_ENTREGADO = 'entregado';
    const ESTADO_CANCELADO = 'cancelado';
    const ESTADO_PAGADO = 'pagado';

    /**
     * Tipos de pedido
     */
    const TIPO_MESA = 'mesa';
    const TIPO_LLEVAR = 'llevar';
    const TIPO_DOMICILIO = 'domicilio';

    /**
     * Relación con mesa
     */
    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }

    /**
     * Relación con usuario (recepcionista)
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación con detalles del pedido
     */
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class);
    }

    /**
     * Relación con pago
     */
    public function pago()
    {
        return $this->hasOne(Pago::class);
    }

    /**
     * Scope para pedidos activos (no completados ni cancelados)
     */
    public function scopeActivos($query)
    {
        return $query->whereNotIn('estado', [self::ESTADO_PAGADO, self::ESTADO_CANCELADO]);
    }

    /**
     * Scope para pedidos pendientes en cocina
     */
    public function scopeParaCocina($query)
    {
        return $query->whereIn('estado', [self::ESTADO_PENDIENTE, self::ESTADO_PREPARANDO]);
    }

    /**
     * Scope para pedidos listos
     */
    public function scopeListos($query)
    {
        return $query->where('estado', self::ESTADO_LISTO);
    }

    /**
     * Calcular el total del pedido
     */
    public function calcularTotal(): float
    {
        $total = $this->detalles->sum(function ($detalle) {
            return $detalle->precio_unitario * $detalle->cantidad;
        });

        return round($total, 2);
    }

    /**
     * Actualizar el total del pedido
     */
    public function actualizarTotal(): void
    {
        $this->total = $this->calcularTotal();
        $this->save();
    }

    /**
     * Verificar si el pedido puede ser editado
     */
    public function puedeEditarse(): bool
    {
        return in_array($this->estado, [self::ESTADO_PENDIENTE, self::ESTADO_PREPARANDO]);
    }

    /**
     * Verificar si el pedido puede ser cancelado
     */
    public function puedeCancelarse(): bool
    {
        return !in_array($this->estado, [self::ESTADO_ENTREGADO, self::ESTADO_PAGADO, self::ESTADO_CANCELADO]);
    }

    /**
     * Obtener el estado formateado
     */
    public function getEstadoFormateadoAttribute(): string
    {
        $estados = [
            'pendiente' => 'Pendiente',
            'preparando' => 'En preparación',
            'listo' => 'Listo para entregar',
            'entregado' => 'Entregado',
            'cancelado' => 'Cancelado',
            'pagado' => 'Pagado',
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
            'cancelado' => 'danger',
            'pagado' => 'dark',
        ];

        return $colores[$this->estado] ?? 'secondary';
    }

    /**
     * Obtener el tipo formateado
     */
    public function getTipoFormateadoAttribute(): string
    {
        $tipos = [
            'mesa' => 'En mesa',
            'llevar' => 'Para llevar',
            'domicilio' => 'A domicilio',
        ];

        return $tipos[$this->tipo] ?? $this->tipo;
    }

    /**
     * Obtener la cantidad total de items
     */
    public function getCantidadItemsAttribute(): int
    {
        return $this->detalles->sum('cantidad');
    }

    /**
     * Obtener el tiempo transcurrido desde la creación
     */
    public function getTiempoTranscurridoAttribute(): string
    {
        $minutos = $this->created_at->diffInMinutes(now());

        if ($minutos < 60) {
            return $minutos . ' min';
        }

        $horas = floor($minutos / 60);
        $minutosRestantes = $minutos % 60;

        return $horas . 'h ' . $minutosRestantes . 'min';
    }
}
