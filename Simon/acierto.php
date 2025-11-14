<?php
session_start();
require_once 'pintar-circulos.php';
echo "<h1>SIMÓN</h1><br><h2>Enhorabuena, has acertado</h2><br>";
$solucion = $_SESSION["solucion"];
pintar_circulos($solucion[0], $solucion[1], $solucion[2], $solucion[3]);
unset($_SESSION["respuesta"]);
echo '<form action="inicio.php" method="post">
        <input type="submit" value="Volver a jugar">
    </form>';
?>