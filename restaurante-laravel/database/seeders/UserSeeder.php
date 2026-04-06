<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario admin temporal — CAMBIAR CREDENCIALES AL INGRESAR POR PRIMERA VEZ
        User::create([
            'nombre'   => 'Administrador',
            'email'    => 'admin@restaurante.com',
            'password' => Hash::make('Admin2024#'),
            'rol'      => 'admin',
            'telefono' => '',
            'activo'   => true,
        ]);
    }
}