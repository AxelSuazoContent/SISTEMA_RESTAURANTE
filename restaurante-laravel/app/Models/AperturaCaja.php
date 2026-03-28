<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AperturaCaja extends Model
{
    protected $table = 'aperturas_caja';

    protected $fillable = [
        'usuario_id',
        'monto_inicial',
        'monto_final',
        'ventas_dia',
        'diferencia',
        'apertura_at',
        'cierre_at',
        'notas',
    ];

    protected $casts = [
        'apertura_at' => 'datetime',
        'cierre_at'   => 'datetime',
        'monto_inicial' => 'decimal:2',
        'monto_final'   => 'decimal:2',
        'ventas_dia'    => 'decimal:2',
        'diferencia'    => 'decimal:2',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    // Verificar si hay una caja abierta hoy
    public static function cajaAbiertaHoy(): ?self
    {
        return self::whereDate('apertura_at', today())
            ->whereNull('cierre_at')
            ->latest()
            ->first();
    }
}