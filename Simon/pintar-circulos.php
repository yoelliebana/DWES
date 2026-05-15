<?php
function pintar_circulos($col1, $col2, $col3, $col4) {
    $colores = [$col1, $col2, $col3, $col4];

    foreach ($colores as $color) {
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