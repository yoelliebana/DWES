<?php
session_start();
require_once 'pintar-circulos.php';
$solucion = $_SESSION["solucion"];
$respuesta = $_SESSION["respuesta"];
echo "<h1>SIMÓN</h1><br><h2>Lo sentimos, has fallado</h2><br>";
echo "<h3>LA COMBINACIÓN ERA:</h3>";
pintar_circulos($solucion[0], $solucion[1], $solucion[2], $solucion[3]);
echo "<h3>SU COMBINACIÓN ELEGIDA FUE:</h3>";
pintar_circulos($respuesta[0], $respuesta[1], $respuesta[2], $respuesta[3]);
unset($_SESSION["respuesta"]);
echo '<form action="inicio.php" method="post">
        <input type="submit" value="Jugar de nuevo">
    </form>';
?>