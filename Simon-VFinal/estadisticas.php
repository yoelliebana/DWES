<?php
session_start();

// Datos de conexión
$hn = 'localhost';
$db = 'bdsimon';
$un = 'root';
$pw = '';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Error de conexión: " . $conn->connect_error);


/*
// Consulta para obtener estadísticas por usuario
$sql = "
    SELECT 
        u.Codigo AS codigousu,
        u.Nombre AS nombre,
        j.numCirc,
        j.numColor,
        SUM(j.acierto = 1) AS aciertos,
        SUM(j.acierto = 0) AS fallos,
        COUNT(j.codjugada) AS total
    FROM usuarios u
    LEFT JOIN jugadas j ON u.Codigo = j.codigousu
    GROUP BY u.Codigo, u.Nombre
    ORDER BY u.Codigo
";
$result = $conn->query($sql);
*/

$numCirc  = intval($_GET['numCirc']);   // o $_POST
$numColor = intval($_GET['numColor']);

$sql = "
    SELECT 
        u.Codigo AS codigousu,
        u.Nombre AS nombre,
        j.numCirc,
        j.numColor,
        SUM(j.acierto = 1) AS aciertos,
        SUM(j.acierto = 0) AS fallos,
        COUNT(j.codjugada) AS total
    FROM usuarios u
    LEFT JOIN jugadas j 
        ON u.Codigo = j.codigousu
        AND j.numCirc = $numCirc
        AND j.numColor = $numColor
    GROUP BY u.Codigo, u.Nombre
    ORDER BY u.Codigo
";
$result = $conn->query($sql);


// Guardamos datos para la tabla y la gráfica
$usuarios = [];
$aciertos = [];
$circulos = [];
$colores = [];


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row['nombre'];
        $aciertos[] = $row['aciertos'];
        $circulos[] = $row['numCirc'];
        $colores[] = $row['numColor'];
        $data[] = $row;
    }
} else {
    echo "No hay datos disponibles.";
}

$conn->close();
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Estadísticas</title>
</head>
<body>
    <h1>Simón</h1><h2>Estadísticas de Jugadas</h2>

    <h1>DIFICULTAD SIMÓN</h1><br><h2>Nº de círculos con los que jugar: </h2><br>
    <form action=inicio.php method="post">
        <select name="numdif">
            <?php
                for ($i = 4; $i <= 8; $i++) {
                    echo "<option value='$i'>$i</option>";
                }
            ?>
        </select>
        <br><br><h2>Nº de colores con los que jugar: </h2><br>
        <select name="numcol">
            <?php
                for ($i = 4; $i <= 8; $i++) {
                    echo "<option value='$i'>$i</option>";
                }
            ?>
        </select>
        <br><br><input type="submit" value="Jugar">
    </form>

    <table border="1">
        <tr>
            <th>Código Usuario</th>
            <th>Nombre</th>
            <th>Número aciertos</th>
            <th>Número de círculos</th>
            <th>Número de colores</th>
        </tr>
        <?php foreach ($data as $row): ?>
        <tr>
            <td><?= $row['codigousu'] ?></td>
            <td><?= $row['nombre'] ?></td>
            <td><?= $row['aciertos'] ?></td>
            <td><?= $row['numCirc'] ?></td>
            <td><?= $row['numColor'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br><form action="inicio.php" method="post" style="display:inline;">
        <input type="submit" value="Volver a jugar">
    </form>
    <form action="login.php" method="post" style="display:inline;">
        <input type="submit" value="Cerrar sesión">
    </form>
</body>
</html>
