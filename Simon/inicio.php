<?php
session_start();
require_once 'pintar-circulos.php';
echo "<h1>SIMÓN</h1><br><h2>Hola, memoriza la combinación</h2><br>";

$colores = ["red", "yellow", "blue", "green"];
$col1 = $colores[array_rand($colores)];
$col2 = $colores[array_rand($colores)];
$col3 = $colores[array_rand($colores)];
$col4 = $colores[array_rand($colores)];

$solucion = [$col1, $col2, $col3, $col4];
$_SESSION["solucion"] = $solucion;

pintar_circulos($col1, $col2, $col3, $col4);

echo '<br><br> <form action="jugar.php" method="post">
        <input type="submit" value="Jugar">
    </form>'
?>