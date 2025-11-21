<?php
function validar_nombre($nombre) {
    return preg_match("/^[a-zAZáéíóúÁÉÍÓÚñÑ\s'-]+$/", $nombre);
}
?>