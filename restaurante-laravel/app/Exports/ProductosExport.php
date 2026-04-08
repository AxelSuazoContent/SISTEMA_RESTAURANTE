<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductosExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Producto::with('categoria')
            ->orderBy('nombre')
            ->get()
            ->map(fn($p) => [
                'Nombre'      => $p->nombre,
                'Categoría'   => $p->categoria->nombre ?? '—',
                'Precio'      => number_format($p->precio, 2),
                'Costo'       => number_format($p->costo ?? 0, 2),
                'Stock'       => $p->stock,
                'Preparación' => $p->preparacion_minutos . ' min',
                'Estado'      => $p->activo ? 'Activo' : 'Inactivo',
            ]);
    }

    public function headings(): array
    {
        return ['Nombre', 'Categoría', 'Precio', 'Costo', 'Stock', 'Preparación', 'Estado'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}