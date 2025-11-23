<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
</head>
<body>
    <h1>AGENDA DE CONTACTOS</h1>
    <form method="POST" action="inicio.php">
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" required>
        <label for="clave">Clave:</label>
        <input type="password" id="clave" name="clave" required>
        <input type="submit" name="login" value="Entrar">
    </form>
</body>
</html>

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
    $stmt = $conn->prepare("SELECT Codigo, Nombre FROM usuarios WHERE Nombre=? AND Clave=?");
    $stmt->bind_param("ss", $name, $clave);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $fila = $res->fetch_assoc();
        $_SESSION['name']   = $fila['Nombre'];
        $_SESSION['codigo'] = $fila['Codigo'];
        $stmt->close();
        header("Location: inicio.php");
        exit();
    } else {
        // crear usuario automáticamente
        $stmtInsert = $conn->prepare("INSERT INTO usuarios (Nombre, Clave, Rol) VALUES (?, ?, 0)");
        $stmtInsert->bind_param("ss", $name, $clave);
        $stmtInsert->execute();
        $_SESSION['name']   = $name;
        $_SESSION['codigo'] = $stmtInsert->insert_id;
        $stmtInsert->close();
        header("Location: inicio.php");
        exit();
    }
}
?>
