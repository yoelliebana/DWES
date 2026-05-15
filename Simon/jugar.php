<?php
session_start();
require_once 'pintar-circulos.php';

echo "<h1>SIMÓN</h1><br><h2>Hola, pulsa los botones en el orden correspondiente</h2><br>";

// Inicializar jugada si no existe
if (!isset($_SESSION["respuesta"])) {
    $_SESSION["respuesta"] = [];
}

// Recuperar la jugada actual
$respuesta = $_SESSION["respuesta"];

$solucion = $_SESSION["solucion"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["rojo"])) $respuesta[] = "red";
    if (isset($_POST["azul"])) $respuesta[] = "blue";
    if (isset($_POST["amari"])) $respuesta[] = "yellow";
    if (isset($_POST["verde"])) $respuesta[] = "green";

    $_SESSION["respuesta"] = $respuesta;
}

$col1 = $respuesta[0] ?? "black";
$col2 = $respuesta[1] ?? "black";
$col3 = $respuesta[2] ?? "black";
$col4 = $respuesta[3] ?? "black";

pintar_circulos($col1, $col2, $col3, $col4);

if (count($respuesta) >= 4) {
    if ($respuesta === $solucion) {
       // $_SESSION["respuesta"] = []; // reiniciar para la próxima partida
        header("Location: acierto.php");
        exit();
    } else {
       // $_SESSION["respuesta"] = []; // reiniciar
        header("Location: fallo.php");
        exit();
    }
}

echo '<form method="post" action="jugar.php">
    <button type="submit" name="rojo" value="red" style="background-color:red; color:white; width:100px; height:50px;">ROJO</button>
    <button type="submit" name="azul" value="blue" style="background-color:blue; color:white; width:100px; height:50px;">AZUL</button>
    <button type="submit" name="amari" value="yellow" style="background-color:yellow; color:black; width:100px; height:50px;">AMARILLO</button>
    <button type="submit" name="verde" value="green" style="background-color:green; color:white; width:100px; height:50px;">VERDE</button>
</form>';
?>
