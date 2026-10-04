<?php
// ===== APARTADO 5: Inicialización de sesión =====
session_name("ud1_XY"); // Cambiar XY por el número de PC
session_start();

if (!isset($_SESSION['servicios'])) {
    $_SESSION['servicios'] = [];
    $_SESSION['numero_version'] = 1;
}

function incrementarVersion() {
    $_SESSION['numero_version']++;
}

// ===== APARTADO 7: Eliminar servicio =====
if ($accion === 'eliminar' && isset($_GET['indice'])) {
    $indice = (int)$_GET['indice'];
    if (isset($_SESSION['servicios'][$indice])) {
        array_splice($_SESSION['servicios'], $indice, 1);
        incrementarVersion();
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// ===== APARTADO 6: Añadir servicio con validación =====
$errores = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'anadir') {
    $codigo = trim($_POST['codigo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $unidades = filter_input(INPUT_POST, 'unidades', FILTER_VALIDATE_INT);
    $precio = filter_input(INPUT_POST, 'precio_unidad', FILTER_VALIDATE_FLOAT);

    if ($codigo === '') $errores[] = "El código es obligatorio.";
    if ($descripcion === '') $errores[] = "La descripción es obligatoria.";
    if ($unidades === false || $unidades <= 0) $errores[] = "Las unidades deben ser un entero mayor que 0.";
    if ($precio === false || $precio < 0) $errores[] = "El precio debe ser un número mayor o igual que 0.";

    if (empty($errores)) {
        $_SESSION['servicios'][] = [
            'codigo' => $codigo,
            'descripcion' => $descripcion,
            'unidades' => $unidades,
            'precio_unidad' => $precio
        ];
        incrementarVersion();
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// ===== APARTADO 6: Cálculo de estadísticas =====
$total_servicios = count($_SESSION['servicios']);
$total_unidades = 0;
$bruto = 0;
foreach ($_SESSION['servicios'] as $s) {
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
    <title>Presupuesto Catering - UD1</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 5px; text-align: left; }
        .errores { color: red; }
    </style>
</head>
<body>
<h1>Gestión de Presupuesto de Catering</h1>
<p>Versión: <?= $_SESSION['numero_version'] ?></p>

<?php if (!empty($errores)): ?>
    <div class="errores">
        <?php foreach ($errores as $e): ?>
            <p><?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- ===== APARTADO 6: Formulario para añadir servicio ===== -->
<h2>Añadir servicio</h2>
<form method="post" action="">
    <input type="hidden" name="accion" value="anadir">
    <label>Código: <input type="text" name="codigo" required></label><br>
    <label>Descripción: <input type="text" name="descripcion" required></label><br>
    <label>Unidades: <input type="number" name="unidades" min="1" required></label><br>
    <label>Precio unidad: <input type="number" step="0.01" name="precio_unidad" min="0" required></label><br>
    <button type="submit">Añadir</button>
</form>

<!-- ===== APARTADO 8: Botón eliminar todo ===== -->
<form method="post" action="">
    <input type="hidden" name="accion" value="eliminar_todo">
    <button type="submit" onclick="return confirm('¿Eliminar todos los datos?');">Eliminar todo</button>
</form>

</body>
</html>