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
        $intentos = $_SESSION["intentos"] ?? [];

        // Datos de conexión
        $hn = 'localhost';
        $db = 'bdsimon';
        $un = 'root';
        $pw = '';

        $conn = new mysqli($hn, $un, $pw, $db);
        if ($conn->connect_error) die("Error de conexión: " . $conn->connect_error);

        $codusu = $_SESSION['id'];

        $sql="INSERT INTO jugadas (codigousu, acierto) VALUES ('$codusu', 0)";
        $result = $conn->query($sql);
        if (!$result) {
            die("Error al insertar la jugada: " . $conn->error);
        }
        $conn->close();
    ?>

    <p>La secuencia correcta es:</p>
    <?php 
        pintarCirculos($solucion[0], $solucion[1], $solucion[2], $solucion[3]);
    ?>

    <p>Tu secuencia fue:</p>
    <?php 
        pintarCirculos($intentos[0], $intentos[1], $intentos[2], $intentos[3]);
    ?>

    <form action="inicio.php" method="post">
        <input type="submit" value="Volver a jugar">
    </form>
    <form action="estadisticas.php" method="post">
        <input type="submit" value="Ver estadísticas">
    </form>
</body>
</html>