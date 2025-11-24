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
    session_start();
    if (isset($_SESSION['name'])) {
        $usuario = $_SESSION['name'];
        echo "<p><strong>Hola $usuario</strong></p>";
        echo "Se han grabado los " . count($_SESSION['imgrandarray']) . " contactos correctamente.";
        echo "<br><a href='index.php'>Volver a loguearse</a><br>";
        echo "<a href='inicio.php'>Introducir más contactos para $usuario</a>";
        echo "<a href='totales.php'>Total de contactos guardados</a>";
    } else {
        echo "<p>No has iniciado sesión. Por favor, vuelve a la página de inicio.</p>";
        echo "<a href='index.php'>Volver a la página de inicio</a>";
    }
    ?>
</body>
</html>