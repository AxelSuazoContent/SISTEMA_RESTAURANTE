<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'costo',
        'categoria_id',
        'imagen',
        'stock',
        'activo',
        'preparacion_minutos',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'costo' => 'decimal:2',
        'activo' => 'boolean',
        'stock' => 'integer',
        'preparacion_minutos' => 'integer',
    ];

    /**
     * Relación con categoría
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Relación con detalles de pedido
     */
    public function detallesPedido()
    {
        return $this->hasMany(DetallePedido::class);
    }

    /**
     * Obtener el nombre de la categoría
     */
    public function getNombreCategoriaAttribute(): string
    {
        return $this->categoria ? $this->categoria->nombre : 'Sin categoría';
    }

    /**
     * Scope para productos activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para productos con stock
     */
    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
