<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class ProductosExport implements ToCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function collection(Collection $collection = null)
    {
        $productos = Producto::with('categoria')->get();
        $totalInventario = $productos->sum(fn($p) => ($p->costo ?? 0) * ($p->stock ?? 0));

        $rows = $productos->map(function ($producto) {
            $margen     = $producto->precio > 0 ? (($producto->precio - ($producto->costo ?? 0)) / $producto->precio) * 100 : 0;
            $valorStock = ($producto->costo ?? 0) * ($producto->stock ?? 0);

            return [
                'ID'           => $producto->id,
                'Nombre'       => $producto->nombre,
                'Categoría'    => $producto->categoria?->nombre ?? 'Sin categoría',
                'Precio'       => 'L ' . number_format($producto->precio, 2),
                'Costo'        => 'L ' . number_format($producto->costo ?? 0, 2),
                'Stock'        => $producto->stock ?? 0,
                'Valor Stock'  => 'L ' . number_format($valorStock, 2),
                'Margen %'     => number_format($margen, 2) . '%',
                'Activo'       => $producto->activo ? 'Sí' : 'No',
                'Descripción'  => $producto->descripcion ?? '',
            ];
        });

        // Fila de totales
        $rows->push([
            '',
            '',
            '',
            '',
            'TOTAL INVENTARIO',
            $productos->sum('stock'),
            'L ' . number_format($totalInventario, 2),
            '',
            '',
            '',
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return ['ID', 'Nombre', 'Categoría', 'Precio', 'Costo', 'Stock', 'Valor Stock', 'Margen %', 'Activo', 'Descripción'];
    }

    public function columnWidths(): array
    {
        return ['A' => 8, 'B' => 30, 'C' => 20, 'D' => 15, 'E' => 15, 'F' => 10, 'G' => 18, 'H' => 12, 'I' => 10, 'J' => 30];
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = $sheet->getHighestRow();
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '2D6A4F']],
                'alignment' => ['horizontal' => 'center'],
            ],
            $lastRow => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '2D6A4F']],
            ],
        ];
    }
}