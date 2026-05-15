<?php
session_start();

$hn = 'localhost';
$un = 'root';
$pw = '';
$db = 'oposicion';
$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die($conn->connect_error);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Oposicion</title>
</head>
<body>
    <form method="post">
        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" required>
        <br>
        <input type="submit" value="ENTRAR">
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dni = $conn->real_escape_string($_POST['dni']);
            $dniAlumno = "SELECT * FROM alumno WHERE dniA='$dni'";
            $resultAlumno = $conn->query($dniAlumno);
            $dniProfesor = "SELECT * FROM profesor WHERE dniP='$dni'";
            $resultProfesor = $conn->query($dniProfesor);
           
            if ($resultAlumno->num_rows > 0) {
                $_SESSION['dni'] = $dni;
                header("Location: ejercicio3.php");
                    exit();
            } elseif ($dniProfesor->num_rows > 0) {
                    $_SESSION['dni'] = $dni;
                    header("Location: ejercicio2.php");
                    exit();
                } else {
                    echo "<p style='color:red;'>DNI no encontrado. Inténtalo de nuevo.</p>";
                }
            }
        $conn->close();
        ?>
    </form>
</body>
</html>