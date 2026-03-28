<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;

    protected $table = 'mesas';

    protected $fillable = [
        'numero',
        'capacidad',
        'ubicacion',
        'estado',
        'hora_reserva',
    ];

    protected $casts = [
        'capacidad'    => 'integer',
        'hora_reserva' => 'datetime:H:i',
    ];

    // ── Constantes de estado ───────────────────────────────────
    const ESTADO_DISPONIBLE = 'disponible';
    const ESTADO_OCUPADA    = 'ocupada';
    const ESTADO_RESERVADA  = 'reservada';
    const ESTADO_INACTIVA   = 'inactiva';

    // ── Accessors ──────────────────────────────────────────────

    public function getEstadoFormateadoAttribute(): string
    {
        return match($this->estado) {
            'disponible' => 'Disponible',
            'ocupada'    => 'Ocupada',
            'reservada'  => 'Reservada',
            'inactiva'   => 'Inactiva',
            default      => ucfirst($this->estado),
        };
    }

    public function getEstadoColorAttribute(): string
    {
        return match($this->estado) {
            'disponible' => 'success',
            'ocupada'    => 'danger',
            'reservada'  => 'warning',
            'inactiva'   => 'secondary',
            default      => 'secondary',
        };
    }

    /**
     * ¿Faltan 30 min o menos para la reserva? (y aún no llegó la hora)
     */
    public function getLibreProntoAttribute(): bool
    {
        if ($this->estado !== 'reservada' || ! $this->hora_reserva) {
            return false;
        }

        $ahora   = now();
        $reserva = \Carbon\Carbon::parse($this->hora_reserva);
        $diff    = $ahora->diffInMinutes($reserva, false);

        return $diff > 0 && $diff <= 30;
    }

    // ── Scopes ─────────────────────────────────────────────────

    public function scopeDisponibles($query)
    {
        return $query->where('estado', self::ESTADO_DISPONIBLE);
    }

    public function scopeActivas($query)
    {
        return $query->where('estado', '!=', self::ESTADO_INACTIVA);
    }

    // ── Helpers ────────────────────────────────────────────────

    public function estaDisponible(): bool
    {
        return $this->estado === self::ESTADO_DISPONIBLE;
    }

    // ── Relaciones ─────────────────────────────────────────────

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function pedidoActivo()
    {
        return $this->hasOne(Pedido::class)
            ->whereIn('estado', ['pendiente', 'preparando', 'listo'])
            ->latest();
    }
}