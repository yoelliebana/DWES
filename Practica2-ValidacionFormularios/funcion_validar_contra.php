<?php
function validar_contra($password, $confirm_password) {
    return $password === $confirm_password;
}
function seg_contra($password) {
    return preg_match("/(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&.,_-])[A-Za-z\d@$!%*#?&.,_-]{8,}/", $password);
}
?>