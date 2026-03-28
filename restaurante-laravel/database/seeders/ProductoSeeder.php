<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            // Entradas
            [
                'nombre' => 'Nachos con Queso',
                'descripcion' => 'Nachos crujientes con queso cheddar derretido',
                'precio' => 85.00,
                'costo' => 35.00,
                'categoria' => 'Entradas',
                'stock' => 50,
                'preparacion_minutos' => 5,
            ],
            [
                'nombre' => 'Quesadillas',
                'descripcion' => 'Quesadillas de flor de queso con guacamole',
                'precio' => 95.00,
                'costo' => 40.00,
                'categoria' => 'Entradas',
                'stock' => 40,
                'preparacion_minutos' => 8,
            ],
            [
                'nombre' => 'Alitas de Pollo',
                'descripcion' => 'Alitas de pollo con salsa BBQ (8 piezas)',
                'precio' => 120.00,
                'costo' => 55.00,
                'categoria' => 'Entradas',
                'stock' => 30,
                'preparacion_minutos' => 12,
            ],
            [
                'nombre' => 'Guacamole con Totopos',
                'descripcion' => 'Guacamole fresco preparado en el momento',
                'precio' => 75.00,
                'costo' => 30.00,
                'categoria' => 'Entradas',
                'stock' => 25,
                'preparacion_minutos' => 5,
            ],

            // Sopas
            [
                'nombre' => 'Sopa de Tortilla',
                'descripcion' => 'Sopa de tortilla con aguacate, queso y crema',
                'precio' => 65.00,
                'costo' => 25.00,
                'categoria' => 'Sopas',
                'stock' => 30,
                'preparacion_minutos' => 10,
            ],
            [
                'nombre' => 'Consomé de Pollo',
                'descripcion' => 'Caldo de pollo con verduras',
                'precio' => 55.00,
                'costo' => 20.00,
                'categoria' => 'Sopas',
                'stock' => 30,
                'preparacion_minutos' => 8,
            ],

            // Ensaladas
            [
                'nombre' => 'Ensalada César',
                'descripcion' => 'Lechuga romana, crutones, queso parmesano y aderezo César',
                'precio' => 90.00,
                'costo' => 35.00,
                'categoria' => 'Ensaladas',
                'stock' => 25,
                'preparacion_minutos' => 5,
            ],
            [
                'nombre' => 'Ensalada Mixta',
                'descripcion' => 'Lechuga, jitomate, cebolla, pepino y aguacate',
                'precio' => 80.00,
                'costo' => 30.00,
                'categoria' => 'Ensaladas',
                'stock' => 25,
                'preparacion_minutos' => 5,
            ],
            [
                'nombre' => 'Ensalada de Pollo',
                'descripcion' => 'Ensalada verde con pechuga de pollo a la parrilla',
                'precio' => 120.00,
                'costo' => 50.00,
                'categoria' => 'Ensaladas',
                'stock' => 20,
                'preparacion_minutos' => 10,
            ],

            // Platos Fuertes
            [
                'nombre' => 'Tacos al Pastor',
                'descripcion' => '5 tacos al pastor con piña, cebolla y cilantro',
                'precio' => 95.00,
                'costo' => 40.00,
                'categoria' => 'Platos Fuertes',
                'stock' => 40,
                'preparacion_minutos' => 10,
            ],
            [
                'nombre' => 'Tacos de Bistec',
                'descripcion' => '5 tacos de bistec con cebolla y cilantro',
                'precio' => 110.00,
                'costo' => 50.00,
                'categoria' => 'Platos Fuertes',
                'stock' => 35,
                'preparacion_minutos' => 10,
            ],
            [
                'nombre' => 'Enchiladas Verdes',
                'descripcion' => '3 enchiladas de pollo con salsa verde, crema y queso',
                'precio' => 115.00,
                'costo' => 45.00,
                'categoria' => 'Platos Fuertes',
                'stock' => 25,
                'preparacion_minutos' => 15,
            ],
            [
                'nombre' => 'Enchiladas Rojas',
                'descripcion' => '3 enchiladas de queso con salsa roja, crema y queso',
                'precio' => 105.00,
                'costo' => 40.00,
                'categoria' => 'Platos Fuertes',
                'stock' => 25,
                'preparacion_minutos' => 15,
            ],
            [
                'nombre' => 'Chiles en Nogada',
                'descripcion' => 'Chile poblano relleno de picadillo con nogada',
                'precio' => 185.00,
                'costo' => 90.00,
                'categoria' => 'Platos Fuertes',
                'stock' => 15,
                'preparacion_minutos' => 20,
            ],
            [
                'nombre' => 'Pechuga a la Parrilla',
                'descripcion' => 'Pechuga de pollo a la parrilla con verduras',
                'precio' => 145.00,
                'costo' => 60.00,
                'categoria' => 'Platos Fuertes',
                'stock' => 20,
                'preparacion_minutos' => 18,
            ],
            [
                'nombre' => 'Carne Asada',
                'descripcion' => 'Corte de carne asada con frijoles y guacamole',
                'precio' => 195.00,
                'costo' => 95.00,
                'categoria' => 'Platos Fuertes',
                'stock' => 18,
                'preparacion_minutos' => 20,
            ],
            [
                'nombre' => 'Filete de Pescado',
                'descripcion' => 'Filete de pescado empanizado con ensalada',
                'precio' => 165.00,
                'costo' => 75.00,
                'categoria' => 'Platos Fuertes',
                'stock' => 15,
                'preparacion_minutos' => 18,
            ],
            [
                'nombre' => 'Hamburguesa Clásica',
                'descripcion' => 'Hamburguesa con queso, lechuga, jitomate y cebolla',
                'precio' => 125.00,
                'costo' => 50.00,
                'categoria' => 'Platos Fuertes',
                'stock' => 30,
                'preparacion_minutos' => 12,
            ],
            [
                'nombre' => 'Pizza Pepperoni',
                'descripcion' => 'Pizza mediana de pepperoni',
                'precio' => 180.00,
                'costo' => 70.00,
                'categoria' => 'Platos Fuertes',
                'stock' => 20,
                'preparacion_minutos' => 20,
            ],

            // Postres
            [
                'nombre' => 'Flan Napolitano',
                'descripcion' => 'Flan casero con caramelo',
                'precio' => 55.00,
                'costo' => 20.00,
                'categoria' => 'Postres',
                'stock' => 20,
                'preparacion_minutos' => 2,
            ],
            [
                'nombre' => 'Pastel de Chocolate',
                'descripcion' => 'Rebanada de pastel de chocolate',
                'precio' => 65.00,
                'costo' => 25.00,
                'categoria' => 'Postres',
                'stock' => 15,
                'preparacion_minutos' => 2,
            ],
            [
                'nombre' => 'Helado (2 bolas)',
                'descripcion' => 'Helado de vainilla, chocolate o fresa',
                'precio' => 45.00,
                'costo' => 15.00,
                'categoria' => 'Postres',
                'stock' => 30,
                'preparacion_minutos' => 2,
            ],
            [
                'nombre' => 'Churros con Chocolate',
                'descripcion' => '4 churros con chocolate caliente',
                'precio' => 70.00,
                'costo' => 25.00,
                'categoria' => 'Postres',
                'stock' => 25,
                'preparacion_minutos' => 8,
            ],

            // Bebidas
            [
                'nombre' => 'Agua de Horchata',
                'descripcion' => 'Agua fresca de horchata (500ml)',
                'precio' => 35.00,
                'costo' => 10.00,
                'categoria' => 'Bebidas',
                'stock' => 50,
                'preparacion_minutos' => 1,
            ],
            [
                'nombre' => 'Agua de Jamaica',
                'descripcion' => 'Agua fresca de jamaica (500ml)',
                'precio' => 35.00,
                'costo' => 10.00,
                'categoria' => 'Bebidas',
                'stock' => 50,
                'preparacion_minutos' => 1,
            ],
            [
                'nombre' => 'Refresco',
                'descripcion' => 'Refresco de cola, lima o naranja (600ml)',
                'precio' => 30.00,
                'costo' => 15.00,
                'categoria' => 'Bebidas',
                'stock' => 60,
                'preparacion_minutos' => 1,
            ],
            [
                'nombre' => 'Limonada',
                'descripcion' => 'Limonada natural o mineral (500ml)',
                'precio' => 40.00,
                'costo' => 12.00,
                'categoria' => 'Bebidas',
                'stock' => 40,
                'preparacion_minutos' => 2,
            ],
            [
                'nombre' => 'Agua Embotellada',
                'descripcion' => 'Agua purificada (600ml)',
                'precio' => 20.00,
                'costo' => 8.00,
                'categoria' => 'Bebidas',
                'stock' => 100,
                'preparacion_minutos' => 1,
            ],
            [
                'nombre' => 'Cerveza Nacional',
                'descripcion' => 'Cerveza nacional (355ml)',
                'precio' => 45.00,
                'costo' => 25.00,
                'categoria' => 'Bebidas',
                'stock' => 80,
                'preparacion_minutos' => 1,
            ],

            // Café y Té
            [
                'nombre' => 'Café Americano',
                'descripcion' => 'Café americano caliente',
                'precio' => 35.00,
                'costo' => 10.00,
                'categoria' => 'Café y Té',
                'stock' => 50,
                'preparacion_minutos' => 3,
            ],
            [
                'nombre' => 'Café con Leche',
                'descripcion' => 'Café con leche caliente',
                'precio' => 40.00,
                'costo' => 12.00,
                'categoria' => 'Café y Té',
                'stock' => 50,
                'preparacion_minutos' => 3,
            ],
            [
                'nombre' => 'Capuchino',
                'descripcion' => 'Capuchino con espuma de leche',
                'precio' => 50.00,
                'costo' => 18.00,
                'categoria' => 'Café y Té',
                'stock' => 40,
                'preparacion_minutos' => 5,
            ],
            [
                'nombre' => 'Té Caliente',
                'descripcion' => 'Té negro, verde o manzanilla',
                'precio' => 30.00,
                'costo' => 8.00,
                'categoria' => 'Café y Té',
                'stock' => 50,
                'preparacion_minutos' => 3,
            ],
        ];

        foreach ($productos as $producto) {
            $categoria = Categoria::where('nombre', $producto['categoria'])->first();
            
            Producto::create([
                'nombre' => $producto['nombre'],
                'descripcion' => $producto['descripcion'],
                'precio' => $producto['precio'],
                'costo' => $producto['costo'],
                'categoria_id' => $categoria->id,
                'stock' => $producto['stock'],
                'preparacion_minutos' => $producto['preparacion_minutos'],
                'activo' => true,
            ]);
        }
    }
}
