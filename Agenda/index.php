<?php
session_start();
unset($_SESSION['imgrandarray']);

$conn = new mysqli("localhost", "root", "", "bdagenda");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if (isset($_POST['login'])) {

    $name = $_POST['name'];
    $clave = $_POST['clave'];

    // BUSCAR USUARIO
    $stmt = $conn->prepare("SELECT Codigo, Clave, Nombre FROM usuarios WHERE Nombre=?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows > 0) {
        $fila = $res->fetch_assoc();

        if ($fila['Clave'] === $clave) {
            $_SESSION['name']   = $fila['Nombre'];
            $_SESSION['codigo'] = $fila['Codigo'];
            $stmt->close();
            header("Location: inicio.php");
            exit();
        } else {
            echo "<p>Clave incorrecta. Inténtalo de nuevo.</p>";
        }
    } else {
        // crear usuario automáticamente
        $stmtInsert = $conn->prepare("INSERT INTO usuarios (Nombre, Clave, Rol) VALUES (?, ?, 0)");
        $stmtInsert->bind_param("ss", $name, $clave);
        $stmtInsert->execute();
        $_SESSION['name']   = $name;
        $_SESSION['codigo'] = $conn->insert_id;
        $stmtInsert->close();
        header("Location: inicio.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
</head>
<body>
    <h1>AGENDA DE CONTACTOS</h1>
    <form method="POST"><table border="1"><tr><td>
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" required>
        <label for="clave">Clave:</label>
        <input type="password" id="clave" name="clave" required>
        <input type="submit" name="login" value="Entrar">
</td></tr></table></form>
</body>
</html>

