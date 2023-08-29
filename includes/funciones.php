<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function esUltimo(string $actual, string $proximo): bool {
    if($actual !== $proximo) {
        return true;
    }
    return false;
}

// Función que revisa que el usuario esté autenticado
function isAuth() : void {
    if(!isset($_SESSION['login'])) {
        header('Location: /');
    }
}

// Función que revisa que esté autenticado como Admin
function isAdmin() : void {
    if(!isset($_SESSION['admin'])) {//if(!isset($_SESSION['admin'])) Si no(!) es un admin entonces lo enviamos a Location: /
        header('Location: /');
    }
}