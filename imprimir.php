<?php
require 'datos.php';

// Cálculos
$total_servicios = count($servicios);
$total_unidades = 0;
$bruto = 0;
foreach ($servicios as $s) {
    $total_unidades += $s['unidades'];
    $bruto += $s['unidades'] * $s['precio_unidad'];
}

$descuento = 0;
if ($bruto > 5000) {
    $descuento = $bruto * 0.15;
} elseif ($bruto >= 3000) {
    $descuento = $bruto * 0.05;
}
$base = $bruto - $descuento;
$iva = $base * 0.10;
$neto = $base + $iva;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Presupuesto Catering</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
    </style>
</head>
<body>
<h1>Presupuesto de Catering</h1>
<table>
    <thead>
        <tr>
            <th>Uds.</th>
            <th>Código</th>
            <th>Descripción</th>
            <th>Precio ud.</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($servicios as $s): ?>
        <tr>
            <td><?= $s['unidades'] ?></td>
            <td><?= htmlspecialchars($s['codigo']) ?></td>
            <td><?= htmlspecialchars($s['descripcion']) ?></td>
            <td><?= number_format($s['precio_unidad'], 2, ',', '.') ?> €</td>
            <td><?= number_format($s['unidades'] * $s['precio_unidad'], 2, ',', '.') ?> €</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Estadísticas</h3>
<ul>
    <li>Total servicios: <?= $total_servicios ?></li>
    <li>Total unidades: <?= $total_unidades ?></li>
    <li>Bruto: <?= number_format($bruto, 2, ',', '.') ?> €</li>
    <li>Descuento: <?= number_format($descuento, 2, ',', '.') ?> €</li>
    <li>IVA (10%): <?= number_format($iva, 2, ',', '.') ?> €</li>
    <li>Neto: <?= number_format($neto, 2, ',', '.') ?> €</li>
</ul>
</body>
</html>