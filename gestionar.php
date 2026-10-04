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
