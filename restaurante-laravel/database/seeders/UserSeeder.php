<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario Administrador
        User::create([
            'nombre' => 'Administrador',
            'email' => 'admin@restaurante.com',
            'password' => Hash::make('password'),
            'rol' => 'admin',
            'telefono' => '555-0100',
            'activo' => true,
        ]);

        // Usuario Recepcionista
        User::create([
            'nombre' => 'María García',
            'email' => 'recepcion@restaurante.com',
            'password' => Hash::make('password'),
            'rol' => 'recepcionista',
            'telefono' => '555-0101',
            'activo' => true,
        ]);

        // Usuario Cocina
        User::create([
            'nombre' => 'Carlos López',
            'email' => 'cocina@restaurante.com',
            'password' => Hash::make('password'),
            'rol' => 'cocina',
            'telefono' => '555-0102',
            'activo' => true,
        ]);

        // Usuarios adicionales
        User::create([
            'nombre' => 'Juan Pérez',
            'email' => 'juan@restaurante.com',
            'password' => Hash::make('password'),
            'rol' => 'recepcionista',
            'telefono' => '555-0103',
            'activo' => true,
        ]);

        User::create([
            'nombre' => 'Ana Martínez',
            'email' => 'ana@restaurante.com',
            'password' => Hash::make('password'),
            'rol' => 'cocina',
            'telefono' => '555-0104',
            'activo' => true,
        ]);
    }
}
