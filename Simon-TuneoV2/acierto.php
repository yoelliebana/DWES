<?php
session_start();
require_once 'pintar-circulos.php';
echo "<h1>SIMÓN</h1><br><h2>Enhorabuena, has acertado</h2><br>";
$solucion = $_SESSION["solucion"];
pintar_circulos($solucion);
unset($_SESSION["respuesta"]);
echo '<form action="dificultad.php" method="post">
        <input type="submit" value="Volver a jugar">
    </form>';
?>