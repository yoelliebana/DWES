<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
</head>
<body>
    <h1>AGENDA</h1>
    <div border="1">
        <?php
        session_start();
        if (isset($_POST['name'])) {
        $_SESSION['name'] = htmlspecialchars($_POST['name']);
}
        $usuario = $_SESSION['name'];
        echo "<p>Hola $usuario, ¿cuántos contactos deseas grabar?<br>
        Puedes grabar entre 1 y 5. Por cada pulsación en INCREMENTAR grabarás un usuario más.<br>
        Cuando el número sea el deseado pulsa GRABAR.</p>"
        ?>
    </div>
    <div border="1">
        <?php
            $imagenes = [
                1 => 'materiales/OIP0.jfif',
                2 => 'materiales/OIP1.jfif',
                3 => 'materiales/OIP2.jfif',
                4 => 'materiales/OIP3.jfif',
                5 => 'materiales/OIP4.jfif'
            ];
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['INCREMENTAR'])) {
                    $indice = rand(1, 5);
                    $imgrandom = $imagenes[$indice];
                    echo "<img src='$imgrandom'";
                }
                if (isset($_POST['GRABAR'])) {
                    header("Location: agenda.php");
            }
        }
            
        ?>
    </div>
    <form method="POST">
        <input type="submit" value="INCREMENTAR">
        <input type="submit" value="GRABAR">
    </form>
</body>
</html>