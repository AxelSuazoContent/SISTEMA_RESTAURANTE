<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = [
        'numero_factura',
        'correlativo',      // ← NUEVO
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

    /**
     * Genera el número correlativo SAR formato: 001-001-01-00000001
     * Valida que esté dentro del rango autorizado.
     * Lanza excepción si el CAI venció o se agotó el rango.
     */
    public static function generarNumero(): array
    {
        $config = ConfigFactura::obtener();

        // ── 1. Validar fecha límite de emisión ──────────────────────────
        if (now()->isAfter($config->fecha_limite_emision->endOfDay())) {
            throw new \Exception(
                'El CAI venció el ' . $config->fecha_limite_emision->format('d/m/Y') .
                '. Actualiza el CAI en Configuración antes de emitir facturas.'
            );
        }

        // ── 2. Extraer el correlativo numérico del rango_desde ──────────
        // Ejemplo: "001-001-01-00000001" → tomamos los últimos 8 dígitos
        $desdeNumero  = (int) str_replace('-', '', substr($config->rango_desde, -8));
        $hastaNumero  = (int) str_replace('-', '', substr($config->rango_hasta, -8));

        // Prefijo: "001-001-01-" (todo menos los últimos 8 dígitos)
        $prefijo = substr($config->rango_desde, 0, 10); // "001-001-01"

        // ── 3. Determinar siguiente correlativo ─────────────────────────
        $ultimoCorrelativo = self::max('correlativo') ?? 0;
        $siguiente         = max($ultimoCorrelativo + 1, $desdeNumero);

        // ── 4. Validar que no se excedió el rango ───────────────────────
        if ($siguiente > $hastaNumero) {
            throw new \Exception(
                'Se agotó el rango de facturas autorizado (' . $config->rango_hasta . '). ' .
                'Solicita un nuevo CAI al SAR.'
            );
        }

        // ── 5. Formatear número SAR: 001-001-01-00000001 ────────────────
        $correlativoFormateado = str_pad($siguiente, 8, '0', STR_PAD_LEFT);
        $numeroFactura         = $prefijo . '-' . $correlativoFormateado;

        return [
            'numero_factura' => $numeroFactura,
            'correlativo'    => $siguiente,
        ];
    }
}