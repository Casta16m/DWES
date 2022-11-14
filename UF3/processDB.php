<?php
require_once './utils.php';
$status = "error"; // per defecte error genèric
$user_email = null;
$user_data = null;

// Resposta al formulari de SIGNIN
if (isset($_POST["method"]) && $_POST["method"] == "signin") {
    // Ens assegurem que hi sigui tot
    if (!isset($_POST["email"]) && !isset($_POST["password"])) die("Incorrect form");

    $user_email = $_POST["email"];
    $data = IniciSessio();
}

// Resposta al formulari de SIGNUP
elseif (isset($_POST["method"]) && $_POST["method"] == "signup") {

    // Ens assegurem que hi sigui tot
    if (!isset($_POST["email"]) && !isset($_POST["password"]) && !isset($_POST["name"])) die("Incorrect form");

    $user_email = $_POST["email"];
    $data = Registra();
}

// Resposta al formulari de tancar la sessió
elseif (isset($_POST["method"]) && $_POST["method"] == "logoff") {
    $status = "logoff";
    $user_email = $_SESSION["user"]["email"] ?? "none";
    session_destroy();
}

// Guarda l'estat a connexions
$cons = $_SESSION['user'];

$cons[] = ["ip" => $_SERVER["REMOTE_ADDR"], "user" => $user_email, "time" => date("Y-m-d H:i:s")];
