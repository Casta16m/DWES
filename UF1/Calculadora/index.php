<!DOCTYPE html>
<html lang="ca">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>Calculadora</title>
</head>

<body>
    <div class="container">
        <form name="calc" class="calculator" method="post">
            <input name="pantalla" type="text" class="value" readonly value="<?php echo mostrarBoto(); ?>" />
            <span class="num clear"><input name="clear" type="submit" value="C"></span>
            <span class="num"><input type="submit" name="num" value="("></span>
            <span class="num"><input type="submit" name="num" value=")"></span>
            <span class="num"><input type="submit" name="SINCOS" value="SIN"></span>
            <span class="num"><input type="submit" name="SINCOS" value="COS"></span>
            <span class="num"><input type="submit" name="num" value="/"></span>
            <span class="num"><input type="submit" name="num" value="*"></span>
            <span class="num"><input type="submit" name="num" value="7"></span>
            <span class="num"><input type="submit" name="num" value="8"></span>
            <span class="num"><input type="submit" name="num" value="9"></span>
            <span class="num"><input type="submit" name="num" value="-"></span>
            <span class="num"><input type="submit" name="num" value="4"></span>
            <span class="num"><input type="submit" name="num" value="5"></span>
            <span class="num"><input type="submit" name="num" value="6"></span>
            <span class="num plus"><input type="submit" name="num" value="+"></span>
            <span class="num"><input type="submit" name="num" value="1"></span>
            <span class="num"><input type="submit" name="num" value="2"></span>
            <span class="num"><input type="submit" name="num" value="3"></span>
            <span class="num"><input type="submit" name="num" value="0"></span>
            <span class="num"><input type="submit" name="num" value="00"></span>
            <span class="num"><input type="submit" name="num" value="."></span>
            <span class="num equal"><input name="igual" type="submit" value="="></span>
        </form>
    </div>

    <?php

    function mostrarBoto()
    {

        if (isset($_POST["pantalla"]) && isset($_POST["num"])) {
            $string = $_POST["pantalla"];
            $string .= $_POST["num"];
        } else if (isset($_POST["pantalla"]) && isset($_POST["SINCOS"])) {
            $string = $_POST["pantalla"];
            $string .= $_POST["SINCOS"] . "(";
        } else if (isset($_POST["pantalla"]) && isset($_POST["igual"])) {
            $string = $_POST["pantalla"];
            return evitarErrors($string);
        } else if (isset($_POST["pantalla"]) && isset($_POST["clear"])) {
            $string = " ";
        } else {
            $string = " ";
        }

        return $string;
    }

    function evitarErrors($string)
    {
        try {
            $string = eval('return' . $string . ';');
            return $string;
        } catch (DivisionByZeroError $e) {
            return 'INF';
        } catch (Error $e) {
            return "ERROR";
        }
    }
    //return preg_match
    ?>
</body>