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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN - JEROGLIFICO</title>
</head>
<body>
    <h1>Iniciar sesión</h1>
    <style>
        p {
            color: red;
        }
    </style>
    <?php
        if (isset($_POST['login'])) {
        $user = $_POST['user'];
        $password = $_POST['password'];

        // BUSCAR USUARIO
        $stmt = $conn->prepare("SELECT login, clave FROM jugador WHERE login=? AND clave=?");
        $stmt->bind_param("ss", $user, $password);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $fila = $res->fetch_assoc();

            if ($fila['clave'] === $password) {
                $_SESSION['user'] = $fila['login'];
                $_SESSION['password']   = $fila['clave'];
                $stmt->close();
                header("Location: inicio.php");
                exit();
        } else {
            echo "<p>Credenciales incorrectas. Inténtalo de nuevo.</p>";
            }
        } else {
            echo "<p>Credenciales incorrectas. Inténtalo de nuevo.</p>";
        } 
    }
    ?>
    <form method="POST">
        <label for="user">Usuario:</label>
        <input type="text" name="user" required>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" required>
        <input type="submit" name="login" value="Entrar">
    </form>
</body>
</html>