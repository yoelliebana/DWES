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

echo "<h1>Fecha: </h1>" . date('d-m-Y') . "<br>";
$sql = "
    SELECT
        SUM(r.respuesta = s.solucion) AS aciertos,
        SUM(r.respuesta != s.solucion) AS fallos
    FROM respuestas r
    JOIN solucion s ON r.fecha = s.fecha
    WHERE r.fecha = CURDATE()
";

$result = $conn->query($sql);
$aciertos = 0;
$fallos = 0;

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $aciertos = $row['aciertos'] ?? 0;
    $fallos   = $row['fallos'] ?? 0;
}

// --- CONSULTA TABLA ACIERTOS ---
$sqlAciertos = "
    SELECT r.login, r.hora
    FROM respuestas r
    JOIN solucion s ON r.fecha = s.fecha
    WHERE r.fecha = CURDATE()
      AND r.respuesta = s.solucion
    ORDER BY r.hora ASC
";
$resAciertos = $conn->query($sqlAciertos);

// --- CONSULTA TABLA FALLOS ---
$sqlFallos = "
    SELECT r.login, r.hora
    FROM respuestas r
    JOIN solucion s ON r.fecha = s.fecha
    WHERE r.fecha = CURDATE()
      AND r.respuesta != s.solucion
    ORDER BY r.hora ASC
";
$resFallos = $conn->query($sqlFallos);


$Update = "
    UPDATE jugador j 
    SET puntos = puntos + 1
    WHERE login in (
        SELECT login FROM respuestas r, solucion
        WHERE respuesta = solucion AND r.fecha = CURDATE() AND r.fecha = solucion.fecha
        )
    ";
$stmtUpdate = $conn->prepare($Update);
$stmtUpdate->execute();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados del día</title>
</head>
<body>
    <h2>Jugadores acertantes: <?php echo $aciertos; ?></h2>
    <table border="1">
        <tr>
            <th>Login</th>
            <th>Hora</th>
        </tr>
        <?php
        if ($resAciertos->num_rows > 0) {
            while ($fila = $resAciertos->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($fila['login']) . "</td><td>" . htmlspecialchars($fila['hora']) . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No hay jugadores acertantes hoy.</td></tr>";
        }
        ?>
    </table>
    <h2>Jugadores no acertantes: <?php echo $fallos; ?></h2>
    <table border="1">
        <tr>
            <th>Login</th>
            <th>Hora</th>
        </tr>
        <?php
        if ($resFallos->num_rows > 0) {
            while ($fila = $resFallos->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($fila['login']) . "</td><td>" . htmlspecialchars($fila['hora']) . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No hay jugadores no acertantes hoy.</td></tr>";
        }
        ?>
    </table>
</body>
</html>