<?php
function pintar_circulos($solucion) {

    foreach ($solucion as $color) {
        echo "<div style='
                width: 100px;
                height: 100px;
                border-radius: 50%;
                background-color: $color;
                display: inline-block;
                margin: 10px;
                border: 2px solid #000;
            '></div>";
    }
}
?>