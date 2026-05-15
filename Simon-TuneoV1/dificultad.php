<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMÓN</title>
</head>
<body>
    <h1>DIFICULTAD SIMÓN</h1><br><h2>Nº de círculos con los que jugar: </h2><br>
    <form action=inicio.php method="post">
        <select name="numdif">
            <?php
                for ($i = 4; $i <= 8; $i++) {
                    echo "<option value='$i'>$i</option>";
                }
            ?>
        </select>
        <br><br><input type="submit" value="Jugar">
    </form>
</body>
</html>