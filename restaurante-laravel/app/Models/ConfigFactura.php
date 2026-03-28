<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigFactura extends Model
{
    protected $table = 'config_factura';

    protected $fillable = [
        'nombre_negocio',
        'rtn',
        'direccion',
        'telefono',
        'cai',
        'rango_desde',
        'rango_hasta',
        'fecha_limite_emision',
    ];

    protected $casts = [
        'fecha_limite_emision' => 'date',
    ];

    // Siempre retorna el único registro o lo crea
    public static function obtener(): self
    {
        return self::firstOrCreate([], [
            'nombre_negocio'       => 'Restaurante Mi Sabor',
            'rtn'                  => '08011999123456',
            'direccion'            => 'Col. Kennedy, Tegucigalpa, Honduras',
            'telefono'             => '2234-5678',
            'cai'                  => 'A1B2C3-D4E5F6-G7H8I9-J0K1L2-M3N4O5-P6',
            'rango_desde'          => '001-001-01-00000001',
            'rango_hasta'          => '001-001-01-00099999',
            'fecha_limite_emision' => '2026-12-31',
        ]);
    }
}