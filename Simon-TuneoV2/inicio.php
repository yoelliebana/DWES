<?php
session_start();
require_once 'pintar-circulos.php';
echo "<h1>SIMÓN</h1><br><h2>Hola, memoriza la combinación</h2><br>";

$numCirculos = $_POST["numdif"];
$_SESSION["numCirculos"] = $numCirculos;
$numColores = $_POST["numcol"];
$_SESSION["numColores"] = $numColores;

$coloresDisp = ["red", "yellow", "blue", "green", "orange", "purple", "pink", "brown"];
$colores = array_slice($coloresDisp, 0, $numColores);
$_SESSION["colores"] = $colores;
$solucion = [];

for ($i = 0; $i < $numCirculos; $i++) {
    $solucion[] = $colores[array_rand($colores)];
}


$_SESSION["solucion"] = $solucion;

pintar_circulos($solucion);

echo '<br><br> <form action="jugar.php" method="post">
        <input type="submit" value="Jugar">
    </form>'
?>