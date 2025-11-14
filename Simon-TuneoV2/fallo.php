<?php
session_start();
require_once 'pintar-circulos.php';
$solucion = $_SESSION["solucion"];
$respuesta = $_SESSION["respuesta"];
echo "<h1>SIMÓN</h1><br><h2>Lo sentimos, has fallado</h2><br>";
echo "<h3>LA COMBINACIÓN ERA:</h3>";
pintar_circulos($solucion);
echo "<h3>SU COMBINACIÓN ELEGIDA FUE:</h3>";
pintar_circulos($respuesta);
unset($_SESSION["respuesta"]);
echo '<form action="dificultad.php" method="post">
        <input type="submit" value="Jugar de nuevo">
    </form>';
?>