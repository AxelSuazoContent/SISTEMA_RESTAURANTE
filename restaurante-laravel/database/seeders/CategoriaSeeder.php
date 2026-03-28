<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Entradas',
                'descripcion' => 'Aperitivos y entradas para comenzar',
                'color' => '#ffc107',
                'orden' => 1,
            ],
            [
                'nombre' => 'Sopas',
                'descripcion' => 'Sopas y caldos',
                'color' => '#17a2b8',
                'orden' => 2,
            ],
            [
                'nombre' => 'Ensaladas',
                'descripcion' => 'Ensaladas frescas',
                'color' => '#28a745',
                'orden' => 3,
            ],
            [
                'nombre' => 'Platos Fuertes',
                'descripcion' => 'Platos principales',
                'color' => '#dc3545',
                'orden' => 4,
            ],
            [
                'nombre' => 'Postres',
                'descripcion' => 'Postres y dulces',
                'color' => '#fd7e14',
                'orden' => 5,
            ],
            [
                'nombre' => 'Bebidas',
                'descripcion' => 'Bebidas y refrescos',
                'color' => '#6f42c1',
                'orden' => 6,
            ],
            [
                'nombre' => 'Café y Té',
                'descripcion' => 'Bebidas calientes',
                'color' => '#795548',
                'orden' => 7,
            ],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }
    }
}
