<?php
session_start();
$hn = 'localhost';
$un = 'root';
$pw = '';
$db = 'oposicion';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);
if (!isset($_SESSION['dni'])) {
    header("Location: ejercicio1.php");
    exit();
}
$dni = $conn->real_escape_string($_SESSION['dni']);

$sql = "
    SELECT curso.*
    FROM profesor
    INNER JOIN curso ON profesor.dniP = curso.profesor
    WHERE profesor.dniP = '$dni'
";

$query = "SELECT nombreP FROM profesor WHERE dniP = '$dni'";
$resultQuery = $conn->query($query);
if ($resultQuery && $resultQuery->num_rows > 0) {
    $rowName = $resultQuery->fetch_assoc();
    $name = $rowName['nombreP'];
} else {
    $name = "Desconocido";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesor - Oposición</title>
</head>
<body>
    <h1>PROFESOR: </h1><?php echo htmlspecialchars($dni); ?><h1>NOMBRE: </h1><?php echo $name ?>
    <table border="1">
        <tr>
            <th>codigocurso</th>
            <th>nombrecurso</th>
            <th>maxalumnos</th>
            <th>fechaini</th>
            <th>fechafin</th>
            <th>numhoras</th>
            <th>profesor</th>
        </tr>
        <?php
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['codigocurso']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nombrecurso']) . "</td>";
                echo "<td>" . htmlspecialchars($row['maxalumnos']) . "</td>";
                echo "<td>" . htmlspecialchars($row['fechaini']) . "</td>";
                echo "<td>" . htmlspecialchars($row['fechafin']) . "</td>";
                echo "<td>" . htmlspecialchars($row['numhoras']) . "</td>";
                echo "<td>" . htmlspecialchars($row['profesor']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='color:red;'>No se encontraron cursos para este profesor.</td></tr>";
        }
        ?>
    </table>
        <h2>Total horas impartidas: </h2><?php
        $totalHorasSql = "
            SELECT SUM(numhoras) AS total_horas
            FROM curso
            WHERE profesor = '$dni'
        ";
        $totalHorasResult = $conn->query($totalHorasSql);
        if ($totalHorasResult && $totalHorasResult->num_rows > 0) {
            $rowTotal = $totalHorasResult->fetch_assoc();
            echo htmlspecialchars($rowTotal['total_horas']);
        } else {
            echo "0";
        }
        ?>
</body>
</html>