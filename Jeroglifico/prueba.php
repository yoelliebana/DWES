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

$aciertos = "
    SELECT r.login, r.hora
    FROM respuestas r
    JOIN solucion s ON r.fecha = s.fecha
    WHERE r.fecha = CURDATE()
      AND r.respuesta = s.solucion
    ORDER BY r.hora ASC
";
$resAciertos = $conn->query($aciertos);
$fallos = "
    SELECT r.login, r.hora
    FROM respuestas r
    JOIN solucion s ON r.fecha = s.fecha
    WHERE r.fecha = CURDATE()
      AND r.respuesta != s.solucion
    ORDER BY r.hora ASC
";
$resFallos = $conn->query($fallos);ç

if ($resAciertos->num_rows > 0) {
    while ($fila = $resAciertos->fetch_assoc()) {
        $Update = "
                UPDATE jugador
                SET puntos = puntos + 1
                WHERE login = $fila['login']
                LIMIT 1
            ";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->execute();
    }

    
}


UPDATE jugador j 
SET puntos = puntos + 1
WHERE login in (
    SELECT login FROM respuestas r, solucion
    WHERE respuesta = solucion AND r.fecha = CURDATE();
)
?>