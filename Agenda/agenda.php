<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AGENDA</title>
</head>
<body>
    <h1>AGENDA</h1>
    <?php
    if (!isset($_SESSION['codigo'])) {
    header("Location: index.php");
    exit();
}
    session_start();
    if (isset($_SESSION['name'])) {
        $usuario = $_SESSION['name'];
        echo "<p>Hola $usuario</p>";
        echo "<form method='POST'><table border='1'>";
        if (isset($_SESSION['imgrandarray'])) {
            $num = 1;
            foreach ($_SESSION['imgrandarray'] as $img) {
                echo "<tr><td>CONTACTO $num<br><label for='name$num'>Nombre $num:</label>
                <input type='text' id='name$num' name='name$num' required><br>
                <label for='email$num'>Email $num:</label>
                <input type='email' id='email$num' name='email$num' required><br>
                <label for='phone$num'>Teléfono $num:</label>
                <input type='tel' id='phone$num' name='phone$num' required></td></tr>";
                $num++;
            }
            echo "</table><input type='submit' name='GRABAR' value='GRABAR'></form>";
        }
    } else {
        echo "<p>No has iniciado sesión. Por favor, vuelve a la página de inicio.</p>";
    }

    if (isset($_POST['GRABAR'])) {
        $hn = 'localhost';
        $un = 'root';
        $pw = '';
        $db = 'bdagenda';

        $conn = new mysqli('localhost', 'root', '', 'bdagenda');
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }
        $numContacts = count($_SESSION['imgrandarray']);
        $codusuario = $_SESSION['codigo'];
        for ($i = 1; $i <= $numContacts; $i++) {
            $name = htmlspecialchars($_POST["name$i"]);
            $email = htmlspecialchars($_POST["email$i"]);
            $phone = htmlspecialchars($_POST["phone$i"]);
            $stmt = $conn->prepare("INSERT INTO contactos (nombre, email, telefono, codusuario) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone, $codusuario);
            $stmt->execute();
            $stmt->close();
        }
        $conn->close();
        unset($_SESSION['imgrandarray']);
        header("Location: grabado.php");
    }
    ?>
</body>
</html>