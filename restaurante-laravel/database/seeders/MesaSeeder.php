<?php

namespace Database\Seeders;

use App\Models\Mesa;
use Illuminate\Database\Seeder;

class MesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mesas del área interior
        for ($i = 1; $i <= 10; $i++) {
            Mesa::create([
                'numero' => $i,
                'capacidad' => $i <= 4 ? 2 : 4,
                'ubicacion' => 'Interior',
                'estado' => 'disponible',
            ]);
        }

        // Mesas del área exterior/terraza
        for ($i = 11; $i <= 16; $i++) {
            Mesa::create([
                'numero' => $i,
                'capacidad' => 4,
                'ubicacion' => 'Terraza',
                'estado' => 'disponible',
            ]);
        }

        // Mesas grandes para grupos
        for ($i = 17; $i <= 20; $i++) {
            Mesa::create([
                'numero' => $i,
                'capacidad' => 8,
                'ubicacion' => 'Salón Privado',
                'estado' => 'disponible',
            ]);
        }
    }
}
