<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'telefono',
        'activo',
    ];

    /**
     * Los atributos que deben estar ocultos para la serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activo' => 'boolean',
    ];

    /**
     * Verificar si el usuario es administrador
     */
    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    /**
     * Verificar si el usuario es recepcionista
     */
    public function esRecepcionista(): bool
    {
        return $this->rol === 'recepcionista';
    }

    /**
     * Verificar si el usuario es cocinero
     */
    public function esCocinero(): bool
    {
        return $this->rol === 'cocina';
    }

    /**
     * Relación con pedidos creados por el usuario
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'usuario_id');
    }
}
