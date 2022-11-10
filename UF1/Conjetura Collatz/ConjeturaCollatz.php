<?php

$n = 34;

echo $n;
echo "<br>";

$arrayBuit = array();
$separador = ", ";
$i = 0;

if ($n <= 0) {
    echo "No posis un numero més vaix de 1";
} else {
    while ($n != 1) {
        if ($n % 2 == 0) {
            $n = $n / 2;
            $arrayBuit[$i] = $n;
            $i++;
        } else {
            $n = 3 * $n + 1;
            $arrayBuit[$i] = $n;
            $i++;
        }
    }

    for ($i = 0; $i < count($arrayBuit); $i++) {
        print_r($arrayBuit[$i]);
        echo ($separador);
    }

    echo "<br>";
    echo max($arrayBuit);
}
