<?php
require_once './utils.php';
$status = "error"; // per defecte error genèric
$user_email = null;
$user_data = null;
$pdo = null;

session_start();

function Registra()
{
    try {
        $hostname = "localhost:3308";
        $dbname = "dwes_sergicastanyer_autpdo";
        $username = "dwes_user";
        $pw = "dwes_pass";
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$username", "$pw");
    } catch (PDOException $e) {
        echo "Failed to get DB handle: " . $e->getMessage() . "\n";
        exit;
    }

    $es_ok = isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']);

    //comprovo que no estigui repetit aquest usuari.
    if ($es_ok) {

        $correu = $_POST['email'];
        $sql = "select count(*) as n from usuaris where Correu_electronic = ?";
        $query = $pdo->prepare($sql);
        $query->execute(array($correu));

        //control d'errors
        $e = $query->errorInfo();
        if ($e[0] != '00000') {
            echo "\nPDO::errorInfo():\n";
            die("Error accedint a dades: " . $e[2]);
        }

        $resultat = $query->fetch();
        $es_ok = ($resultat['n'] == 0);
    }

    if ($es_ok) {

        //inserció i redirect
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['password'] = $_POST['password'];
        $_SESSION['login_time_stamp'] = time();

        $sql = "insert into usuaris values (?, ?, md5(?))";
        $query = $pdo->prepare($sql);
        $query->execute(array($_SESSION['name'], $_SESSION['email'], $_SESSION['password']));

        $e = $query->errorInfo();
        if ($e[0] != '00000') {
            echo "\nPDO::errorInfo():\n";
            die("Error accedint a dades: " . $e[2]);
        }

        setcookie("msg", "Usuari " . $_SESSION['name'] . "insertat correctament.");
        die(header('Location: ./index.php'));
    }
}

function IniciSessio()
{
    try {
        $hostname = "localhost:3308";
        $dbname = "dwes_sergicastanyer_autpdo";
        $username = "dwes_user";
        $pw = "dwes_pass";
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", "$username", "$pw");
    } catch (PDOException $e) {
        echo "Failed to get DB handle: " . $e->getMessage() . "\n";
        exit;
    }

    $es_ok = isset($_POST['email']) && isset($_POST['password']);

    //comprovo que Correu_electronic existexi

    if ($es_ok) {

        $correu = $_POST['email'];
        $contrasenya = md5($_POST['password']);
        $sql = "select count(*) as n from usuaris where Correu_electronic = ? and Contrasenya = ?";
        $query = $pdo->prepare($sql);
        $query->execute(array($correu, $contrasenya));
        $result = $query->fetchColumn();
        print_r($result);

        if (!$query->execute()) {
            die("Ha fallat la consulta, comprova usuari, passwd, bd, nom taula i nom columna");
        }

        if ($row = $query->fetch()) {
            if ($row['n'] == $contrasenya) {
                $sql = "select Nom from usuaris where Correu_electronic = ?";
                $query = $pdo->prepare($sql);
                $query->execute(array($correu));

                if ($row = $query->fetch()) {
                }

                header("Location: hola.php"); // per defecte 302
                $user_data["login_time_stamp"] = time();
                $_SESSION['name'] = mysqli_fetch_assoc($correu);
                $_SESSION["user"] = $user_data;
                $_SESSION['email'] = $correu;
            } else {
                die(header('Location: ./index.php'));
            }
        } else {
            die(header('Location: ./index.php'));
        }
    }
}

function print_conns(string $email): string
{
    $output = "";
    $data = $_SESSION['user'];

    foreach ($data as $vals) {
        if ($vals["user"] == $email && str_contains($vals["status"], "success"))
            $output .= "Connexió des de " . $vals["ip"] . " amb data " . $vals["time"] . "<br>\n";
    }

    return $output;
}
