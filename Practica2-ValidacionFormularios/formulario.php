<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validación-Formulario</title>
</head>
<body>
    <h1>Validar Formulario PHP</h1>
    <form method="POST">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" title="Solo se admiten letras y espacios">
        <br><label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required title="Debe tener al menos una letra, un número, un carácter especial (@$!%*#?&.,_-) y mínimo 8 caracteres.">
        <br><label for="confirm_password">Confirmar Contraseña:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <br><label for="web">Web:</label>
        <input type="url" id="web" name="web" required>
        <br><label for="comment">Comentarios:</label>
        <textarea id="comment" name="comment" required></textarea>
        <br><label for="genero">Género:</label>
        <input type="radio" id="masculino" name="genero" value="Masculino" required>Masculino
        <input type="radio" id="femenino" name="genero" value="Femenino" required>Femenino
        <br>
        <input type="submit" value="Enviar">
    </form>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $host = "localhost";
            $db = "bdvalidarformulario";
            $user = "root";
            $pass = "";

            $conn = new mysqli($host, $user, $pass, $db);
            if ($conn->connect_error) {
                die("Conexión fallida: " . $conn->connect_error);
            }

            $nombre = htmlspecialchars($_POST['nombre'] ?? '');
            $email = htmlspecialchars($_POST['email'] ?? '');
            $web = htmlspecialchars($_POST['web'] ?? '');
            $comment = htmlspecialchars($_POST['comment'] ?? '');
            $genero = htmlspecialchars($_POST['genero'] ?? '');
            $password = htmlspecialchars($_POST['password'] ?? '');
            $confirm_password = htmlspecialchars($_POST['confirm_password'] ?? '');

            $errores = [];
            include 'funcion_validar_email.php';
            include 'funcion_validar_url.php';
            include 'funcion_validar_contra.php';
            include 'funcion_validar_nombre.php';
            if ($genero == "Masculino") {
                $genbinario = 1;
            } else {
                $genbinario = 0;
            }
            if (!validar_email($email)) {
                $errores[] = "Email no válido.";
            }
            if (!validar_url($web)) {
                $errores[] = "URL no válida.";
            }
            if (!validar_contra($password, $confirm_password)) {
                $errores[] = "Las contraseñas no coinciden.";
            }
            if (!seg_contra($password)) {
                $errores[] = "La contraseña no es segura. Debe tener al menos una letra, un número, un carácter especial (@$!%*#?&.,_-) y mínimo 8 caracteres.";
            }
            if (!validar_nombre($nombre)) {
                $errores[] = "Nombre no válido. Solo se permiten letras y espacios.";
            }
            if (!empty($errores)) {
                foreach ($errores as $error) {
                    echo "<strong>Error:</strong> " . $error . "<br>";
                }
                exit;
            } else {
                $password_hash=password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, web, comentarios, genero, password) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $nombre, $email, $web, $comment, $genbinario, $password_hash);

                if ($stmt->execute()) {
                    echo "<h2>Usuario registrado correctamente:</h2>";
                    echo "<strong>Nombre:</strong> " . $nombre . "<br>";
                    echo "<strong>Email:</strong> " . $email . "<br>";
                    echo "<strong>Web:</strong> " . $web . "<br>";
                    echo "<strong>Comentarios:</strong> " . $comment . "<br>";
                    echo "<strong>Género:</strong> " . $genero . "<br>";
                } else {
                    echo "Error al registrar el usuario: " . $stmt->error;
                }
                $stmt->close();
                $conn->close();
            }
        }
    ?>
</body>
</html>