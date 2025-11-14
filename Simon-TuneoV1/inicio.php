<?php
session_start();
require_once 'pintar-circulos.php';
echo "<h1>SIMÓN</h1><br><h2>Hola, memoriza la combinación</h2><br>";

$numCirculos = $_POST["numdif"];
$_SESSION["numCirculos"] = $numCirculos;

$colores = ["red", "yellow", "blue", "green"];
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