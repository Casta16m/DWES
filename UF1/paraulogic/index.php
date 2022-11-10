<?php

require_once "helper.php";

global $date, $hexagon;
global $trobat, $count_trobat, $errormsg;

session_start();
init();

/**
 * Funció principal. Comprova els metòdes GET, i regenera l'hexàgon si s'escau
 *
 * @return void
 */
function init(): void
{
    global $date;
    global $hexagon;
    global $trobat, $count_trobat, $errormsg;

    $trobat = "";
    $count_trobat = 0;
    $errormsg = "";

    if (isset($_GET["data"])) {
        if (date_is_valid($_GET["data"])) {
            $_SESSION["forcedata"] = $_GET["data"];
        } else {
            unset($_SESSION["forcedata"]);
        }
    }

    $date = $_SESSION["forcedata"] ?? today();

    if (!isset($_GET["force"]) && isset($_SESSION["data"]) && $_SESSION["data"] == $date
        && isset($_SESSION["hexagon"]) && isset($_SESSION["solucio"])) {
        $hexagon = $_SESSION["hexagon"];
    } else {
        // Torna a crear l'hexàgon
        $start_time = microtime(true);
        list($hexagon, $solucio) = crea_hexagon_op($date);
        $end_time = microtime(true);
        echo "TIME:" .($end_time - $start_time);

        $_SESSION["hexagon"] = $hexagon;
        $_SESSION["data"] = $date;
        $_SESSION["solucio"] = $solucio;
        $_SESSION["trobat"] = null;
        $_SESSION["last_error"] = null;
    }

    if (isset($_GET["sol"])) {
        echo "SOLUCIÓ <br>";
        print_r(filter_words($hexagon));
    }

    if (isset($_GET["neteja"])) {
        $_SESSION["trobat"] = null;
    }

    if (isset($_GET["barreja"])) {
        //Separa l'array i aplica un shuffle
        $primerapart = array_slice($hexagon, 0, 3);
        $segonapart = array_slice($hexagon, 4);
        $merge = array_merge($primerapart, $segonapart);
        shuffle($merge);
        $novaprimerapart = $merge;
        $novasegonapart = $merge;
        array_splice($novaprimerapart, 0, 3);
        array_splice($novasegonapart, 3);
        $hexagon = array_merge($novaprimerapart, [$hexagon[3]], $novasegonapart);

        $_SESSION["hexagon"] = $hexagon;
    }

    if (isset($_SESSION["trobat"])) {
        $trobat = implode(", ", $_SESSION["trobat"]);
        $count_trobat = count($_SESSION["trobat"]);
    }

    if (isset($_GET["error"])) {
        switch ($_GET["error"]) {
            case 0: // Enviat sense paraula
                $errormsg = "";
                break;
            case 1:
                $errormsg = $_SESSION["last_error"] ?? "Error";
                break;
            case 2:
                $errormsg = "Ja hi és";
                break;
            case 3:
                $errormsg = "Falta la lletra del mig";
                break;
            default: // Error genèric
                $errormsg = "Error";
        }
    }
}

/**
 * Retorna el codi HTML per una peça de l'hexàgon
 *
 * @param string $lletra
 * @param bool $center
 * @return string
 */
function peca_hexagon(string $lletra, bool $center): string
{
    $extra = $center ? 'id="center-letter"' : "";
    return "<li class='hex'>
                <div class='hex-in'><a class='hex-link' data-lletra='$lletra' draggable='false' $extra><p>$lletra</p></a></div>
                </li>";
}

/**
 * Dibuixa l'hexàgon
 *
 * @param $hexagon
 * @return string
 */
function dibuixa_hexagon($hexagon): string
{
    $out = "";
    $count = 0;
    foreach ($hexagon as $lletra) {
        $out .= peca_hexagon($lletra, $count == 3) . "\n";
        $count++;
    }
    return $out;
}

?>

<!DOCTYPE html>
<html lang="ca">
<head>
    <title>PHPògic</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Juga al PHPògic.">
    <link href="//fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body data-joc="<?= $date ?>">
<form class="main" action="process.php" method="post">
    <h1>
        <a href=""><img src="logo.png" height="54" class="logo" alt="PHPlògic"></a>
    </h1>

    <div class="container-notifications">
        <?php
        if ($errormsg) { ?>
            <p class="hide" id="message" style=""><?= $errormsg ?></p>
        <?php
        } ?>
    </div>

    <div class="cursor-container">
        <input type="hidden" name="word" id="input-hidden-word">
        <p id="input-word"><span id="test-word"></span><span id="cursor">|</span></p>
    </div>

    <div class="container-hexgrid">
        <ul id="hex-grid">
            <?= dibuixa_hexagon($hexagon); ?>
        </ul>
    </div>

    <div class="button-container">
        <button id="delete-button" type="button" title="Suprimeix l'última lletra" onclick="suprimeix()"> Suprimeix
        </button>
        <button id="shuffle-button" type="button" class="icon" aria-label="Barreja les lletres"
                title="Barreja les lletres" onclick="window.location = '?barreja'">
            <svg width="16" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 512 512">
                <path fill="currentColor"
                      d="M370.72 133.28C339.458 104.008 298.888 87.962 255.848 88c-77.458.068-144.328 53.178-162.791 126.85-1.344 5.363-6.122 9.15-11.651 9.15H24.103c-7.498 0-13.194-6.807-11.807-14.176C33.933 94.924 134.813 8 256 8c66.448 0 126.791 26.136 171.315 68.685L463.03 40.97C478.149 25.851 504 36.559 504 57.941V192c0 13.255-10.745 24-24 24H345.941c-21.382 0-32.09-25.851-16.971-40.971l41.75-41.749zM32 296h134.059c21.382 0 32.09 25.851 16.971 40.971l-41.75 41.75c31.262 29.273 71.835 45.319 114.876 45.28 77.418-.07 144.315-53.144 162.787-126.849 1.344-5.363 6.122-9.15 11.651-9.15h57.304c7.498 0 13.194 6.807 11.807 14.176C478.067 417.076 377.187 504 256 504c-66.448 0-126.791-26.136-171.315-68.685L48.97 471.03C33.851 486.149 8 475.441 8 454.059V320c0-13.255 10.745-24 24-24z"></path>
            </svg>
        </button>
        <button id="submit-button" type="submit" title="Introdueix la paraula">Introdueix</button>
    </div>

    <div class="scoreboard">
        <div>Has trobat <span id="letters-found"><?= $count_trobat ?></span> <span
                    id="found-suffix"><?= $count_trobat == 0 ? "funcions" : ($count_trobat == 1 ? "funció: " : "funcions: ") ?></span><span
                    id="discovered-text"><?= $trobat ?>.</span>
        </div>
        <div id="score"></div>
        <div id="level"></div>
    </div>
</form>
<script>
    function clear_gets() {
        window.history.replaceState(null, null, window.location.pathname)
    }

    function amagaError() {
        if (document.getElementById("message"))
            document.getElementById("message").style.opacity = "0"
        clear_gets()
    }

    function afegeixLletra(lletra) {
        document.getElementById("test-word").innerHTML += lletra
        document.getElementById("input-hidden-word").value += lletra
        amagaError()
    }

    function suprimeix() {
        document.getElementById("test-word").innerHTML = document.getElementById("test-word").innerHTML.slice(0, -1)
        document.getElementById("input-hidden-word").value = document.getElementById("input-hidden-word").value.slice(0, -1)
        amagaError()
    }

    function isLetter(str) {
        return str.length === 1 && str.match(/[a-z]/i);
    }

    window.onload = () => {
        // Afegeix funcionalitat al click de les lletres
        Array.from(document.getElementsByClassName("hex-link")).forEach((el) => {
            el.onclick = () => {
                afegeixLletra(el.getAttribute("data-lletra"))
            }
        })

        setTimeout(amagaError, 2000)

        //Anima el cursor
        let estat_cursor = true;
        setInterval(() => {
            document.getElementById("cursor").style.opacity = estat_cursor ? "1" : "0"
            estat_cursor = !estat_cursor
        }, 500)
    }

    window.onkeydown = function (event) {
        if (isLetter(event.key))
            afegeixLletra(event.key)
        else if (event.code === "Backspace")
            suprimeix()
        else if (event.code === "Enter")
            document.getElementById("submit-button").click()
    }

</script>
</body>
</html>
