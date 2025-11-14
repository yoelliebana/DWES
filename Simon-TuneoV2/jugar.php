<?php
session_start();
require_once 'pintar-circulos.php';

echo "<h1>SIMÓN</h1><br><h2>Hola, pulsa los botones en el orden correspondiente</h2><br>";

// Recuperar número de círculos elegido
$numCirculos = $_SESSION["numCirculos"];

// Recuperar colores usados
$numColores = $_SESSION["numColores"];
$colores = $_SESSION["colores"];
// Inicializar jugada si no existe
if (!isset($_SESSION["respuesta"])) {
    $_SESSION["respuesta"] = [];
}

// Recuperar la jugada actual
$respuesta = $_SESSION["respuesta"];
$solucion = $_SESSION["solucion"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($colores as $color) {
        if (isset($_POST[$color])) {
            $respuesta[] = $color;
        }
    }
    $_SESSION["respuesta"] = $respuesta;
}


$circulosMostrar = [];
for ($i = 0; $i < $numCirculos; $i++) {
    $circulosMostrar[] = $respuesta[$i] ?? "black";
}

pintar_circulos($circulosMostrar);

if (count($respuesta) >= $numCirculos) {
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

// Traducir colores disponibles
$traduccion = [
    "red" => "Rojo",
    "yellow" => "Amarillo",
    "blue" => "Azul",
    "green" => "Verde",
    "orange" => "Naranja",
    "purple" => "Morado",
    "pink" => "Rosa",
    "brown" => "Marrón"
];

echo '<form method="post" action="jugar.php">';
    foreach ($colores as $color) {
        $name = $traduccion[$color] ?? $color;
        $textColor = ($color == "yellow") ? "black" : "white";

        echo "<button type='submit' name='$color' value='$color' style='background-color:$color; color:$textColor; width:100px; height:50px;'>$name</button>";  
    }
echo '</form>';
?>
