<?php
//estadisticas de la agenda
session_start();
$hn = 'localhost';
$un = 'root';
$pw = '';
$db = 'bdagenda';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta: obtener todos los usuarios y el número de contactos que tiene cada uno
$sql = "SELECT u.Codigo AS codusuario, u.Nombre AS nombre, COUNT(c.codcontacto) AS num_contactos
        FROM usuarios u
        LEFT JOIN contactos c ON u.Codigo = c.codusuario
        GROUP BY u.Codigo, u.Nombre
        ORDER BY u.Codigo";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>AGENDA</title>
</head>
<body>
    <h1>AGENDA</h1>
    <?php
    $usuario = $_SESSION['name'];
     echo "<p><strong>Hola $usuario</strong></p>";
    ?>

    <table border="1">
        <thead>
            <tr>
                <th>Código usuario</th>
                <th>Nombre</th>
                <th>Número de contactos</th>
                <th>Gráfica</th>
            </tr>
        </thead>
        <tbody>
            <style>
                .dot {
                    display: inline-block;
                    width: 12px;
                    height: 12px;
                    margin: 2px;
                    background-color: red;
                    border-radius: 50%;
                }
            </style>
        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $cod = htmlspecialchars($row['codusuario']);
                $nombre = htmlspecialchars($row['nombre']);
                $num = (int)$row['num_contactos'];
                echo "<tr>";
                echo "<td>$cod</td>";
                echo "<td>$nombre</td>";
                echo "<td>$num</td>";
                echo "<td>";
                // Mostrar un punto rojo por cada contacto
                for ($i = 0; $i < $num; $i++) {
                    echo "<span class='dot'></span>";
                }
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No hay usuarios registrados.</td></tr>";
        }
        // liberar resultado y cerrar conexión
        if ($result) { $result->free(); }
        $conn->close();
        ?>
        </tbody>
    </table>
<?php
echo "<a href='index.php'>Volver a loguearse</a>";
$usuario = $_SESSION['name'];
echo "<br><a href='inicio.php'>Introducir más contactos para $usuario</a>";

?>
</body>
</html>
