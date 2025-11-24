<?php
session_start();

$hn = "localhost";
$un = "jugador";
$pw = "jugador";
$db = "jeroglifico";
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUNTOS - JEROGLIFICO</title>
</head>
<body>
    <h1>Puntos por jugador</h1>
    <?php
    $sql = "
        SELECT r.login, COUNT(*) AS puntos
        FROM respuestas r
        JOIN solucion s ON r.fecha = s.fecha
        WHERE r.respuesta = s.solucion
        GROUP BY r.login
        ORDER BY puntos DESC
    ";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Login</th>
                    <th>Puntos</th>

                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['login']) . "</td>
                    <td>" . $row['puntos'] . "</td>
                    <td style='background-color:blue; width:" . ($row['puntos'] * 10) . "px;'>&nbsp;</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No hay datos de puntos disponibles.</p>";
    }
    ?>
</body>
</html>