<?php

namespace App\Exports;

use App\Models\Pedido;
use App\Models\Producto;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class VentasExport implements WithMultipleSheets
{
    protected $fechaInicio;
    protected $fechaFin;

    public function __construct($fechaInicio, $fechaFin)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin    = $fechaFin;
    }

    public function sheets(): array
    {
        return [
            new VentasResumenSheet($this->fechaInicio, $this->fechaFin),
            new VentasDetalleSheet($this->fechaInicio, $this->fechaFin),
            new VentasTopProductosSheet($this->fechaInicio, $this->fechaFin),
            new EstadoResultadosSheet($this->fechaInicio, $this->fechaFin),
            new FlujoCajaSheet($this->fechaInicio, $this->fechaFin),
            new BalanceInventarioSheet(),
            new PuntoEquilibrioSheet($this->fechaInicio, $this->fechaFin),
            new MargenGananciaSheet(),
        ];
    }
}

// ==================== HOJA 1: RESUMEN ====================
class VentasResumenSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $fechaInicio, $fechaFin;
    public function __construct($fechaInicio, $fechaFin) { $this->fechaInicio = $fechaInicio; $this->fechaFin = $fechaFin; }
    public function title(): string { return 'Resumen'; }

    public function collection()
    {
        $ventas = Pedido::whereBetween('created_at', [$this->fechaInicio, $this->fechaFin])
            ->where('estado', 'pagado')->with('pago')->get();

        $total         = $ventas->sum('total');
        $cantidad      = $ventas->count();
        $promedio      = $cantidad > 0 ? $total / $cantidad : 0;
        $efectivo      = $ventas->filter(fn($v) => $v->pago?->metodo_pago === 'efectivo')->sum('total');
        $tarjeta       = $ventas->filter(fn($v) => $v->pago?->metodo_pago === 'tarjeta')->sum('total');
        $transferencia = $ventas->filter(fn($v) => $v->pago?->metodo_pago === 'transferencia')->sum('total');

        return collect([
            ['Período',            $this->fechaInicio->format('d/m/Y') . ' — ' . $this->fechaFin->format('d/m/Y')],
            ['', ''],
            ['MÉTRICA',            'VALOR'],
            ['Total Ventas',       'L ' . number_format($total, 2)],
            ['Total Pedidos',      $cantidad],
            ['Ticket Promedio',    'L ' . number_format($promedio, 2)],
            ['', ''],
            ['POR MÉTODO DE PAGO', ''],
            ['Efectivo',           'L ' . number_format($efectivo, 2)],
            ['Tarjeta',            'L ' . number_format($tarjeta, 2)],
            ['Transferencia',      'L ' . number_format($transferencia, 2)],
        ]);
    }

    public function headings(): array { return ['', '']; }
    public function columnWidths(): array { return ['A' => 30, 'B' => 25]; }
    public function styles(Worksheet $sheet): array
    {
        return [
            1  => ['font' => ['bold' => true, 'size' => 13]],
            3  => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1a5276']]],
            4  => ['fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'EAF4FB']]],
            5  => ['fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'EAF4FB']]],
            6  => ['fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'EAF4FB']]],
            8  => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1e8449']]],
            9  => ['fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'EAFAF1']]],
            10 => ['fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'EAFAF1']]],
            11 => ['fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'EAFAF1']]],
        ];
    }
}

// ==================== HOJA 2: DETALLE ====================
class VentasDetalleSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $fechaInicio, $fechaFin;
    public function __construct($fechaInicio, $fechaFin) { $this->fechaInicio = $fechaInicio; $this->fechaFin = $fechaFin; }
    public function title(): string { return 'Detalle de Ventas'; }

    public function collection()
    {
        return Pedido::whereBetween('created_at', [$this->fechaInicio, $this->fechaFin])
            ->where('estado', 'pagado')->with(['mesa', 'usuario', 'pago'])
            ->orderBy('created_at', 'desc')->get()
            ->map(fn($v) => [
                '#'       => $v->id,
                'Fecha'   => $v->created_at->format('d/m/Y H:i'),
                'Mesa'    => $v->mesa ? 'Mesa ' . $v->mesa->numero : 'Sin mesa',
                'Atendió' => $v->usuario->nombre ?? '-',
                'Método'  => $v->pago?->metodo_pago ?? '-',
                'Total'   => 'L ' . number_format($v->total, 2),
            ]);
    }

    public function headings(): array { return ['# Pedido', 'Fecha', 'Mesa', 'Atendió', 'Método de Pago', 'Total']; }
    public function columnWidths(): array { return ['A' => 12, 'B' => 20, 'C' => 12, 'D' => 20, 'E' => 18, 'F' => 15]; }
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1a5276']], 'alignment' => ['horizontal' => 'center']],
        ];
    }
}

// ==================== HOJA 3: TOP PRODUCTOS ====================
class VentasTopProductosSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $fechaInicio, $fechaFin;
    public function __construct($fechaInicio, $fechaFin) { $this->fechaInicio = $fechaInicio; $this->fechaFin = $fechaFin; }
    public function title(): string { return 'Top Productos'; }

    public function collection()
    {
        return \DB::table('detalles_pedido')
            ->join('productos', 'detalles_pedido.producto_id', '=', 'productos.id')
            ->join('pedidos', 'detalles_pedido.pedido_id', '=', 'pedidos.id')
            ->whereBetween('pedidos.created_at', [$this->fechaInicio, $this->fechaFin])
            ->where('pedidos.estado', 'pagado')
            ->select('productos.nombre', \DB::raw('SUM(detalles_pedido.cantidad) as total_vendido'), \DB::raw('SUM(detalles_pedido.cantidad * detalles_pedido.precio_unitario) as total_ingresos'))
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('total_vendido')
            ->get()
            ->map(fn($p) => [
                'Producto'       => $p->nombre,
                'Unidades'       => $p->total_vendido,
                'Total Ingresos' => 'L ' . number_format($p->total_ingresos, 2),
            ]);
    }

    public function headings(): array { return ['Producto', 'Unidades Vendidas', 'Total Ingresos']; }
    public function columnWidths(): array { return ['A' => 30, 'B' => 20, 'C' => 20]; }
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1e8449']], 'alignment' => ['horizontal' => 'center']],
        ];
    }
}

// ==================== HOJA 4: ESTADO DE RESULTADOS ====================
class EstadoResultadosSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $fechaInicio, $fechaFin;
    public function __construct($fechaInicio, $fechaFin) { $this->fechaInicio = $fechaInicio; $this->fechaFin = $fechaFin; }
    public function title(): string { return 'Estado de Resultados'; }

    public function collection()
    {
        $ventas = Pedido::whereBetween('created_at', [$this->fechaInicio, $this->fechaFin])
            ->where('estado', 'pagado')->with('detalles.producto')->get();

        $ingresos = $ventas->sum('total');
        $costos   = $ventas->sum(fn($p) => $p->detalles->sum(fn($d) => ($d->producto->costo ?? 0) * $d->cantidad));
        $ganancia = $ingresos - $costos;
        $margen   = $ingresos > 0 ? ($ganancia / $ingresos) * 100 : 0;

        return collect([
            ['ESTADO DE RESULTADOS', ''],
            ['Período', $this->fechaInicio->format('d/m/Y') . ' — ' . $this->fechaFin->format('d/m/Y')],
            ['', ''],
            ['CONCEPTO',             'MONTO'],
            ['(+) Ingresos por Ventas', 'L ' . number_format($ingresos, 2)],
            ['(-) Costo de Ventas',     'L ' . number_format($costos, 2)],
            ['(=) Utilidad Bruta',      'L ' . number_format($ganancia, 2)],
            ['', ''],
            ['Margen de Utilidad',      number_format($margen, 2) . '%'],
        ]);
    }

    public function headings(): array { return ['', '']; }
    public function columnWidths(): array { return ['A' => 35, 'B' => 25]; }
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '6c3483']]],
            4 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '6c3483']]],
            5 => ['fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F5EEF8']]],
            6 => ['fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FADBD8']]],
            7 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D5F5E3']]],
            9 => ['font' => ['bold' => true, 'color' => ['rgb' => '1e8449']]],
        ];
    }
}

// ==================== HOJA 5: FLUJO DE CAJA ====================
class FlujoCajaSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $fechaInicio, $fechaFin;
    public function __construct($fechaInicio, $fechaFin) { $this->fechaInicio = $fechaInicio; $this->fechaFin = $fechaFin; }
    public function title(): string { return 'Flujo de Caja'; }

    public function collection()
    {
        return Pedido::whereBetween('created_at', [$this->fechaInicio, $this->fechaFin])
            ->where('estado', 'pagado')->with('pago')
            ->selectRaw('DATE(created_at) as fecha, SUM(total) as total, COUNT(*) as pedidos')
            ->groupBy('fecha')->orderBy('fecha')->get()
            ->map(fn($d) => [
                'Fecha'   => $d->fecha,
                'Pedidos' => $d->pedidos,
                'Ingresos'=> 'L ' . number_format($d->total, 2),
            ]);
    }

    public function headings(): array { return ['Fecha', 'Pedidos', 'Ingresos del Día']; }
    public function columnWidths(): array { return ['A' => 20, 'B' => 15, 'C' => 20]; }
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1a5276']], 'alignment' => ['horizontal' => 'center']],
        ];
    }
}

// ==================== HOJA 6: BALANCE DE INVENTARIO ====================
class BalanceInventarioSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    public function title(): string { return 'Balance Inventario'; }

    public function collection()
    {
        $productos = Producto::with('categoria')->where('activo', true)->get();
        $totalValor = $productos->sum(fn($p) => ($p->costo ?? 0) * ($p->stock ?? 0));

        $rows = $productos->map(fn($p) => [
            'Producto'   => $p->nombre,
            'Categoría'  => $p->categoria?->nombre ?? '-',
            'Stock'      => $p->stock ?? 0,
            'Costo Unit' => 'L ' . number_format($p->costo ?? 0, 2),
            'Valor Total'=> 'L ' . number_format(($p->costo ?? 0) * ($p->stock ?? 0), 2),
        ]);

        $rows->push(['', '', '', 'TOTAL INVENTARIO', 'L ' . number_format($totalValor, 2)]);

        return $rows;
    }

    public function headings(): array { return ['Producto', 'Categoría', 'Stock', 'Costo Unitario', 'Valor Total']; }
    public function columnWidths(): array { return ['A' => 30, 'B' => 20, 'C' => 10, 'D' => 18, 'E' => 18]; }
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'b7950b']], 'alignment' => ['horizontal' => 'center']],
        ];
    }
}

// ==================== HOJA 7: PUNTO DE EQUILIBRIO ====================
class PuntoEquilibrioSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    protected $fechaInicio, $fechaFin;
    public function __construct($fechaInicio, $fechaFin) { $this->fechaInicio = $fechaInicio; $this->fechaFin = $fechaFin; }
    public function title(): string { return 'Punto de Equilibrio'; }

    public function collection()
    {
        $ventas   = Pedido::whereBetween('created_at', [$this->fechaInicio, $this->fechaFin])
            ->where('estado', 'pagado')->with('detalles.producto')->get();

        $ingresos = $ventas->sum('total');
        $costos   = $ventas->sum(fn($p) => $p->detalles->sum(fn($d) => ($d->producto->costo ?? 0) * $d->cantidad));
        $margenPorc = $ingresos > 0 ? ($ingresos - $costos) / $ingresos : 0;
        $pedidos  = $ventas->count();
        $ticketProm = $pedidos > 0 ? $ingresos / $pedidos : 0;

        return collect([
            ['ANÁLISIS DE PUNTO DE EQUILIBRIO', ''],
            ['', ''],
            ['CONCEPTO',                'VALOR'],
            ['Ingresos Totales',        'L ' . number_format($ingresos, 2)],
            ['Costos Variables',        'L ' . number_format($costos, 2)],
            ['Margen de Contribución',  number_format($margenPorc * 100, 2) . '%'],
            ['', ''],
            ['Ticket Promedio',         'L ' . number_format($ticketProm, 2)],
            ['Total Pedidos',           $pedidos],
        ]);
    }

    public function headings(): array { return ['', '']; }
    public function columnWidths(): array { return ['A' => 35, 'B' => 25]; }
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '922b21']]],
            3 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '922b21']]],
            4 => ['fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FDEDEC']]],
            5 => ['fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FDEDEC']]],
            6 => ['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'D5F5E3']]],
        ];
    }
}

// ==================== HOJA 8: MARGEN DE GANANCIA ====================
class MargenGananciaSheet implements FromCollection, WithTitle, WithHeadings, WithStyles, WithColumnWidths
{
    public function title(): string { return 'Margen de Ganancia'; }

    public function collection()
    {
        return Producto::with('categoria')->where('activo', true)->get()
            ->map(function($p) {
                $margen = $p->precio > 0 ? (($p->precio - ($p->costo ?? 0)) / $p->precio) * 100 : 0;
                return [
                    'Producto'    => $p->nombre,
                    'Categoría'   => $p->categoria?->nombre ?? '-',
                    'Precio Venta'=> 'L ' . number_format($p->precio, 2),
                    'Costo'       => 'L ' . number_format($p->costo ?? 0, 2),
                    'Ganancia'    => 'L ' . number_format($p->precio - ($p->costo ?? 0), 2),
                    'Margen %'    => number_format($margen, 2) . '%',
                ];
            });
    }

    public function headings(): array { return ['Producto', 'Categoría', 'Precio Venta', 'Costo', 'Ganancia', 'Margen %']; }
    public function columnWidths(): array { return ['A' => 30, 'B' => 20, 'C' => 15, 'D' => 15, 'E' => 15, 'F' => 12]; }
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '1e8449']], 'alignment' => ['horizontal' => 'center']],
        ];
    }
}