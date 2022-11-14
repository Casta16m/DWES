<?php
require_once './utils.php';

// Redirecció per sessió no activa
if (!isset($_SESSION["user"]) || time() - $_SESSION["user"]["login_time_stamp"] > 60) {
    header("Location: index.php?error=timeout", true, 303);
}

?>
<!DOCTYPE html>
<html lang="ca">

<head>
    <title>Benvingut</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">

</head>

<body>
    <div class="container noheight" id="container">
        <div class="welcome-container">
            <h1>Benvingut!</h1>
            <div>Hola <?= $_SESSION["user"]["name"] ?>, les teves darreres connexions són:</div>
            <div class="connections"> <?= print_conns($_SESSION["user"]["email"]) ?> </div>
            <form action="index.php" method="post">
                <input type="hidden" name="method" value="logoff" />
                <button>Tanca la sessió</button>
            </form>
        </div>
    </div>
</body>

</html>