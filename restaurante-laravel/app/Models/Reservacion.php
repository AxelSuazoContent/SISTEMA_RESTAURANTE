<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservacion extends Model
{
    protected $table = 'reservaciones'; // ← LÍNEA AGREGADA

    protected $fillable = [
        'mesa_id', 'cliente_nombre', 'cliente_telefono',
        'fecha', 'hora', 'notas', 'estado', 'usuario_id'
    ];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeVigentes($query)
    {
        return $query->where('estado', 'pendiente')
            ->where(function ($q) {
                $q->where('fecha', '>', today())
                  ->orWhere(function ($q2) {
                      $q2->where('fecha', today())
                         ->where('hora', '>=', now()->format('H:i:s'));
                  });
            });
    }
}