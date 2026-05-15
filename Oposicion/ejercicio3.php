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

$query = "SELECT nombreA FROM alumno WHERE dniA = '$dni'";
$resultQuery = $conn->query($query);
if ($resultQuery && $resultQuery->num_rows > 0) {
    $rowName = $resultQuery->fetch_assoc();
    $name = $rowName['nombreA'];
} else {
    $name = "Desconocido";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matricular - Oposicion</title>
</head>
<body>
    <h1>ALUMNO: </h1><?php echo htmlspecialchars($dni); ?><h1>NOMBRE: </h1><?php echo $name ?>
    <form method="post">
        <label for="dni">DNI: </label>
        <input type="text" name="dni" value="<?php echo htmlspecialchars($dni); ?>" readonly>
        <br>
        <label for="codigocurso">COD CURSO: </label>
        <input type="text" name="codigocurso" required>
        <br>
        <label for="pruebaA">PRUEBA A: </label>
        <input type="number" name="pruebaA" value="<?php echo $_POST['pruebaA'] ?? '';?>" required>
        <br>
        <label for="pruebaB">PRUEBA B: </label>
        <input type="number" name="pruebaB" value="<?php echo $_POST['pruebaB'] ?? '';?>" required>
        <br>
        <label for="tipo">TIPO: </label>
        <input type="text" name="tipo" value="<?php echo $_POST['tipo'] ?? '';?>"required>
        <br>
        <label for="inscripcion">INSCRIPCION: </label>
        <input type="date" name="inscripcion" value="<?php echo $_POST['inscripcion'] ?? '';?>" required>
        <br>
        <input type="submit" value="GUARDAR">
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $dni = $conn->real_escape_string($_POST['dni']);
        $codigocurso = $conn->real_escape_string($_POST['codigocurso']);
        $pruebaA = (int)$_POST['pruebaA'];
        $pruebaB = (int)$_POST['pruebaB'];
        $tipo = $conn->real_escape_string($_POST['tipo']);
        $inscripcion = $conn->real_escape_string($_POST['inscripcion']);

        $sqlcod = "SELECT * FROM curso WHERE codigocurso = '$codigocurso'";
        $resultcod = $conn->query($sqlcod);
        if ($resultcod->num_rows == 0) {
            echo "<p style='color:red;'>El código de curso no existe. Inténtalo de nuevo.</p>";
            $_POST['codigocurso'] = '';
            $_POST['pruebaA'] = $pruebaA;
            $_POST['pruebaB'] = $pruebaB;
            $_POST['tipo'] = $tipo;
            $_POST['inscripcion'] = $inscripcion;
        } else {
            $sqlInsert = "
                INSERT INTO matricula (dnialumno, codcurso, pruebaA, pruebaB, tipo, inscripcion)
                VALUES ('$dni', '$codigocurso', $pruebaA, $pruebaB, '$tipo', '$inscripcion')
            ";
            if ($conn->query($sqlInsert) === TRUE) {
                echo "<p>La matrícula del alumno $dni en el curso $codigocurso se ha realizado correctamente</p>";
            } else {
                echo "<p style='color:red;'>Error al guardar la matrícula: " . $conn->error . "</p>";
            }
        }
    }
    ?>
</body>
</html>