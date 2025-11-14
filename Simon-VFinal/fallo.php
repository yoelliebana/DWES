<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simon</title>
</head>
<body>
    <h1>Simón</h1>
    <?php
        session_start();
        require_once 'pintarCirculos.php';

        $solucion = $_SESSION["solucion"];
        $respuesta = $_SESSION["respuesta"];

        // Datos de conexión
        $hn = 'localhost';
        $db = 'bdsimon';
        $un = 'root';
        $pw = '';

        $conn = new mysqli($hn, $un, $pw, $db);
        if ($conn->connect_error) die("Error de conexión: " . $conn->connect_error);

        $codusu = $_SESSION['id'];
        $numCirc = count($solucion);
        $numColores = $_SESSION["numColores"];

        $sql="INSERT INTO jugadas (codigousu, acierto, numCirc, numColor) VALUES ('$codusu', 0, '$numCirc', '$numColores')";
        $result = $conn->query($sql);
        if (!$result) {
            die("Error al insertar la jugada: " . $conn->error);
        }
        $conn->close();
    ?>

    <p>La secuencia correcta era:</p>
    <?php 
        pintar_circulos($solucion);
    ?>

    <p>Tu secuencia fue:</p>
    <?php 
        pintar_circulos($respuesta);
        unset($_SESSION["respuesta"]);
    ?>

    <form action="inicio.php" method="post">
        <input type="submit" value="Volver a jugar">
    </form>
    <form action="estadisticas.php" method="post">
        <input type="submit" value="Ver estadísticas">
    </form>
</body>
</html>