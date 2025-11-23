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
            if (!isset($_SESSION['imgrandarray'])) {
                $_SESSION['imgrandarray'] = [];
            }

            $imagenes = [
                1 => 'materiales/OIP0.jfif',
                2 => 'materiales/OIP1.jfif',
                3 => 'materiales/OIP2.jfif',
                4 => 'materiales/OIP3.jfif',
                5 => 'materiales/OIP4.jfif'
            ];
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['INCREMENTAR'])) {
                    if (count($_SESSION['imgrandarray']) >= 5) { 
                        header("Location: agenda.php");
                        exit();
                    }
                    $indice = rand(1, 5);
                    $imgrandom = $imagenes[$indice];
                    $_SESSION['imgrandarray'][] = $imgrandom;
                    foreach ($_SESSION['imgrandarray'] as $img) {
                        echo "<img src='$img' width='100' height='100'>";
                    }
                }
                if (isset($_POST['GRABAR'])) {
                    header("Location: agenda.php");
            }
        }
            
        ?>
    </div>
    <form method="POST">
        <input type="submit" name="INCREMENTAR" value="INCREMENTAR">
        <input type="submit" name="GRABAR" value="GRABAR">
    </form>
</body>
</html>