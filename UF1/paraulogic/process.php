<?php

require_once "helper.php";
session_start();
$error = null;

$_SESSION["last_error"] = "";

if (!isset($_SESSION["trobat"])) {
    $_SESSION["trobat"] = [];
}

// Comprova els metodes POST i SESSION
if (!isset($_POST["word"]) || !isset($_SESSION["hexagon"]) || !isset($_SESSION["solucio"])) {
    $error = -1;
}

if (empty($_POST["word"])) {
    $error = 0; // Enviat sense paraula
} elseif (!has_center($_SESSION["hexagon"], $_POST["word"])) {
    $error = 3; // Falta la lletra del mig
} elseif (!word_is_valid($_POST["word"])) {
    $error = 1; // No vàlid. Mateixa paraula
    $_SESSION["last_error"] = $_POST["word"];
} elseif (in_array($_POST["word"], $_SESSION["trobat"])) {
    $error = 2; // Ja hi és
}

// Fes les redireccions PGR, en funció de l'error
if ($error !== null) {
    header("Location: index.php?error=$error", true, 303);
} else {
    $_SESSION["trobat"][] = $_POST["word"];
    header('Location: index.php');
}
