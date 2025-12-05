<?php
session_start();
$hn = "localhost";
$un = "Jugador";
$pw = "";
$db = "jeroglifico";
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_POST['user'])) {
        $_SESSION['user'] = htmlspecialchars($_POST['user']);
}

$usuario = $_SESSION['user'];

$stmt = $conn->prepare("SELECT nombre FROM jugador WHERE login=?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    $fila = $res->fetch_assoc();
    $nombre = $fila['nombre'];
} else {
    $nombre = "Usuario";
}

echo "<h1>Bienvenido/a, $nombre!</h1>";
echo "<img src='imagen/20240216.jpg'>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INICIO - JEROGLIFICO</title>
</head>
<body>
    <form method="POST">
        <label for="solution">Solución al jeroglífico</label>
        <input type="text" name="solution" required>
        <input type="submit" name="enviar" value="Enviar">
    </form>
    <?php
    if (isset($_POST['enviar'])) {
        $solucion = $_POST['solution'];

        // Obtener la solución correcta del día
        $sqlSol = "SELECT solucion FROM solucion WHERE fecha = CURDATE()";
        $resSol = $conn->query($sqlSol); 

    if ($resSol->num_rows > 0) {
        $solucionHoy = $resSol->fetch_assoc()['solucion'];

        // Comprobar si ya acertó hoy (evitar sumar más de 1 punto)
        $sqlCheck = "
            SELECT 1
            FROM respuestas
            WHERE login = ?
                AND fecha = CURDATE()
                AND respuesta = ?
            LIMIT 1
        ";

        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param("ss", $usuario, $solucionHoy);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        $stmt = $conn->prepare("INSERT INTO respuestas (fecha, login, hora, respuesta) VALUES (CURDATE(), ?, CURTIME(), ?)");
        $stmt->bind_param("ss", $usuario, $solucion);
        $stmt->execute();
        $stmt->close();

        /*
        // Si es su primer acierto del día → sumar 1 punto
        if ($resultCheck->num_rows == 0 && strcasecmp($solucion, $solucionHoy) == 0) {
            $sqlUpdate = "
                UPDATE jugador
                SET puntos = puntos + 1
                WHERE login = ?
                LIMIT 1
            ";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bind_param("s", $usuario);
            $stmtUpdate->execute();
        }
        */
}

    }
    ?>
    <br><a href="puntos.php">Ver puntos por jugador</a><br>
    <a href="resultado.php">Resultado del día</a>
</body>
</html>