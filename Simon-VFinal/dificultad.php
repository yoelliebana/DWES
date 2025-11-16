v<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIMÓN</title>

<style>
.container {
    margin-bottom: 25px;
}

.label {
    font-size: 20px;
    margin-bottom: 5px;
    font-weight: bold;
}

.stepper {
    display: flex;
    align-items: center;
    gap: 10px;
}

.stepper button {
    width: 40px;
    height: 40px;
    font-size: 22px;
    cursor: pointer;
}

.value-box {
    width: 40px;
    text-align: center;
    font-weight: bold;
    font-size: 18px;
}

input[type=range] {
    width: 200px;
}
</style>
</head>
<body>

<h1>DIFICULTAD SIMÓN</h1><br>

<form action="inicio.php" method="post">

<!-- Selector círculos -->
<div class="container">
    <div class="label">Nº de círculos con los que jugar:</div>

    <div class="stepper">
        <button type="button" onclick="ajustar('circulos', -1)"><</button>
        <span id="circulos_val" class="value-box">4</span>
        <button type="button" onclick="ajustar('circulos', 1)">></button>
    </div>

    <input type="range" id="circulos_slider" min="4" max="8" value="4" oninput="syncSlider('circulos')">
    <input type="hidden" name="numdif" id="circulos" value="4">
</div>

<!-- Selector colores -->
<div class="container">
    <div class="label">Nº de colores con los que jugar:</div>

    <div class="stepper">
        <button type="button" onclick="ajustar('colores', -1)"><</button>
        <span id="colores_val" class="value-box">4</span>
        <button type="button" onclick="ajustar('colores', 1)">></button>
    </div>

    <input type="range" id="colores_slider" min="4" max="8" value="4" oninput="syncSlider('colores')">
    <input type="hidden" name="numcol" id="colores" value="4">
</div>

<br>
<input type="submit" value="Jugar">

</form>

<script>
function ajustar(campo, cambio) {
    let input = document.getElementById(campo);
    let display = document.getElementById(campo + "_val");
    let slider = document.getElementById(campo + "_slider");

    let valor = parseInt(input.value) + cambio;
    if (valor < 4) valor = 4;
    if (valor > 8) valor = 8;

    input.value = valor;
    display.textContent = valor;
    slider.value = valor;
}

function syncSlider(campo) {
    let slider = document.getElementById(campo + "_slider");
    let input = document.getElementById(campo);
    let display = document.getElementById(campo + "_val");

    input.value = slider.value;
    display.textContent = slider.value;
}
</script>

</body>
</html>
