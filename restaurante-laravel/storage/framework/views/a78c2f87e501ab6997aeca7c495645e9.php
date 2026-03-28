<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura <?php echo e($factura->numero_factura); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 13px; color: #000; background: #fff; padding: 20px; }
        .factura { max-width: 400px; margin: 0 auto; }
        .header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 12px; margin-bottom: 12px; }
        .header h1 { font-size: 18px; font-weight: 900; letter-spacing: 1px; }
        .header p { font-size: 11px; color: #333; margin-top: 3px; }
        .numero-factura { text-align: center; font-size: 14px; font-weight: 700; margin: 10px 0; }
        .info table { width: 100%; }
        .info td { padding: 2px 0; font-size: 12px; }
        .info td:last-child { text-align: right; }
        .divider { border: none; border-top: 1px dashed #000; margin: 10px 0; }
        .items table { width: 100%; border-collapse: collapse; }
        .items th { font-size: 11px; text-transform: uppercase; padding: 4px 0; border-bottom: 1px solid #000; }
        .items th:last-child { text-align: right; }
        .items td { padding: 5px 0; font-size: 12px; vertical-align: top; }
        .items td:last-child { text-align: right; font-weight: 700; }
        .items td.cant { width: 30px; color: #555; }
        .items td.precio { width: 60px; text-align: right; color: #555; font-size: 11px; }
        .totales table { width: 100%; }
        .totales td { padding: 3px 0; font-size: 13px; }
        .totales td:last-child { text-align: right; font-weight: 700; }
        .total-final { font-size: 16px; font-weight: 900; border-top: 2px solid #000; padding-top: 6px; margin-top: 4px; }
        .sar-box { margin-top: 12px; padding: 8px; border: 1px dashed #555; font-size: 10px; color: #333; }
        .sar-box p { margin: 2px 0; }
        .footer { text-align: center; margin-top: 12px; padding-top: 10px; border-top: 2px dashed #000; font-size: 11px; color: #555; }
        .no-print { text-align: center; margin-top: 20px; }
        .no-print button { padding: 10px 30px; font-size: 14px; cursor: pointer; border: 2px solid #000; background: #000; color: #fff; border-radius: 6px; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

<?php $config = \App\Models\ConfigFactura::obtener(); ?>

<div class="factura">

    
    <div class="header">
        <h1><?php echo e(strtoupper($config->nombre_negocio)); ?></h1>
        <p>RTN: <?php echo e($config->rtn); ?></p>
        <p><?php echo e($config->direccion); ?></p>
        <p>Tel: <?php echo e($config->telefono); ?></p>
    </div>

    
    <div class="numero-factura">
        FACTURA <?php echo e($factura->numero_factura); ?>

    </div>

    
    <div class="info" style="margin-bottom:10px">
        <table>
            <tr>
                <td>Cliente / Mesa</td>
                <td><?php echo e($factura->cliente_nombre ?: 'Consumidor Final'); ?></td>
            </tr>
            <tr>
                <td>RTN Cliente</td>
                <td>000-000-000-0000</td>
            </tr>
            <tr>
                <td>Atendió</td>
                <td><?php echo e($factura->usuario->nombre); ?></td>
            </tr>
            <tr>
                <td>Fecha</td>
                <td><?php echo e($factura->created_at->format('d/m/Y H:i')); ?></td>
            </tr>
        </table>
    </div>

    <hr class="divider">

    
    <div class="items">
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th style="text-align:center">Cant</th>
                    <th style="text-align:right">Precio</th>
                    <th style="text-align:right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $factura->pedido->detalles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detalle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($detalle->producto->nombre); ?></td>
                    <td class="cant" style="text-align:center"><?php echo e($detalle->cantidad); ?></td>
                    <td class="precio">$<?php echo e(number_format($detalle->precio_unitario, 2)); ?></td>
                    <td>$<?php echo e(number_format($detalle->cantidad * $detalle->precio_unitario, 2)); ?></td>
                </tr>
                <?php if($detalle->notas): ?>
                <tr>
                    <td colspan="4" style="font-size:10px;color:#666;padding-left:8px">
                        * <?php echo e($detalle->notas); ?>

                    </td>
                </tr>
                <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <hr class="divider">

    
<?php
    $subtotalSinIsv = $factura->subtotal / 1.15;
    $isv = $factura->subtotal - $subtotalSinIsv;
?>
<div class="totales">
    <table>
        <tr>
            <td>Subtotal exento</td>
            <td>L. 0.00</td>
        </tr>
        <tr>
            <td>Subtotal gravado 15%</td>
            <td>L. <?php echo e(number_format($subtotalSinIsv, 2)); ?></td>
        </tr>
        <tr>
            <td>ISV 15%</td>
            <td>L. <?php echo e(number_format($isv, 2)); ?></td>
        </tr>
        <tr class="total-final">
            <td>TOTAL A PAGAR</td>
            <td>L. <?php echo e(number_format($factura->total, 2)); ?></td>
        </tr>
    </table>
</div>

    
    <div style="text-align:center;margin-top:8px;font-size:12px">
        Forma de pago: <strong><?php echo e(strtoupper($factura->metodo_pago)); ?></strong>
    </div>

    
    <div class="sar-box">
        <p><strong>CAI:</strong> <?php echo e($config->cai); ?></p>
        <p><strong>Rango autorizado:</strong></p>
        <p>Del <?php echo e($config->rango_desde); ?></p>
        <p>Al &nbsp; <?php echo e($config->rango_hasta); ?></p>
        <p><strong>Fecha límite de emisión:</strong> <?php echo e($config->fecha_limite_emision->format('d/m/Y')); ?></p>
        <p style="margin-top:4px">* Original: Cliente &nbsp;|&nbsp; Copia: Establecimiento</p>
    </div>

    
    <div class="footer">
        <p>¡Gracias por su visita!</p>
        <p>Vuelva pronto 😊</p>
    </div>

</div>

<div class="no-print">
    <button onclick="window.print()">🖨 Imprimir Factura</button>
</div>

</body>
</html><?php /**PATH C:\Users\Ryzen Gaming\Documents\SISTEMA DE RESTAURANTE\restaurante-laravel\resources\views/admin/facturas/imprimir.blade.php ENDPATH**/ ?>