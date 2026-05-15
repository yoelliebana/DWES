<?php
session_start();

// Datos de conexión
$hn = 'localhost';
$db = 'bdsimon';
$un = 'root';
$pw = '';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die("Error de conexión: " . $conn->connect_error);

$data = [];      // Array donde guardaremos las filas
$haySeleccion = false;

// Si el usuario ha enviado el formulario
if (isset($_POST['numdif']) && isset($_POST['numcol'])) {

    $numCirc  = intval($_POST['numdif']);
    $numColor = intval($_POST['numcol']);
    $haySeleccion = true;

    // SQL filtrado por los valores elegidos
    $sql = "
        SELECT 
            u.Codigo AS codigousu,
            u.Nombre AS nombre,
            $numCirc  AS numCirc,
            $numColor AS numColor,
            SUM(j.acierto = 1) AS aciertos
        FROM usuarios u
        LEFT JOIN jugadas j 
            ON u.Codigo = j.codigousu
            AND j.numCirc = $numCirc
            AND j.numColor = $numColor
        GROUP BY u.Codigo, u.Nombre
        ORDER BY u.Codigo
    ";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
}

$conn->close();
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <title>Estadísticas</title>
</head>

<body>

    <h1>Simón – Estadísticas</h1>

    <h2>Selecciona dificultad</h2>

    <form action="estadisticas.php" method="post">

        <label>Nº de círculos:</label><br>
        <select name="numdif">
            <?php for ($i = 4; $i <= 8; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?></option>
            <?php endfor; ?>
        </select>

        <br><br>

        <label>Nº de colores:</label><br>
        <select name="numcol">
            <?php for ($i = 4; $i <= 8; $i++): ?>
                <option value="<?= $i ?>"><?= $i ?></option>
            <?php endfor; ?>
        </select>

        <br><br>
        <input type="submit" value="Mostrar estadísticas">

    </form>

    <hr>

<?php if ($haySeleccion): ?>

    <h2>Estadísticas para <?php $numCirc ?> círculos y <?php $numColor ?> colores</h2>

    <table border="1">
        <tr>
            <th>Código Usuario</th>
            <th>Nombre</th>
            <th>Aciertos</th>
            <th>Nº de círculos</th>
            <th>Nº de colores</th>
        </tr>

        <?php foreach ($data as $row): ?>
        <tr>
            <td><?= $row['codigousu'] ?></td>
            <td><?= $row['nombre'] ?></td>
            <td><?= $row['aciertos'] ?></td>
            <td><?= $numCirc ?></td>
            <td><?= $numColor ?></td>
        </tr>
        <?php endforeach; ?>

    </table>

<?php endif; ?>

    <br>

    <form action="inicio.php" method="post" style="display:inline;">
        <input type="submit" value="Volver a jugar">
    </form>

    <form action="login.php" method="post" style="display:inline;">
        <input type="submit" value="Cerrar sesión">
    </form>

</body>
</html>
