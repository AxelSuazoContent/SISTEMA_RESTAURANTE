<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = [
        'numero_factura',
        'pedido_id',
        'pago_id',
        'usuario_id',
        'subtotal',
        'impuesto',
        'total',
        'metodo_pago',
        'cliente_nombre',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function pago()
    {
        return $this->belongsTo(Pago::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    // Genera el número de factura automáticamente
    public static function generarNumero(): string
    {
        $anio  = now()->format('Y');
        $ultimo = self::whereYear('created_at', $anio)->max('id') ?? 0;
        return 'FAC-' . $anio . '-' . str_pad($ultimo + 1, 5, '0', STR_PAD_LEFT);
    }
}