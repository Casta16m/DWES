<?php
function creaMatriu ($num) {
    $arrayMatriu = array();
    // Per saber les ubicacions fem dos comptadors: "i", "j".

    for ($i = 0; $i < $num; $i++) { // for per les files
        for ($j = 0; $j < $num; $j++) { // for per les columnes
            if ($i < $j) { // part superior
                $arrayMatriu[$i][$j] = $i + $j;
            } else if ($i > $j) { // part inferior
                $arrayMatriu[$i][$j] = rand(10, 20);
            } else { // en el cas de que els dos comptadors siguin =, ens posara *
                $arrayMatriu[$i][$j] = "*";
            }
        }
    }
    return $arrayMatriu; // returnem l'array completa per mostrar-la amb la següent funció
}

function mostraMatriu ($arrayMatriu) {
    $s = '<table border="1">'; // A $s li posem els bordes de la taula
    foreach ( $arrayMatriu as $i ) {
        $s .= '<tr>'; // <tr> ens serveix per separar les files de les columnes i <td> serà al reves
        foreach ( $i as $j ) {
            $s .= '<td>'.$j.'</td>';
        }
        $s .= '</tr>';
    }
    $s .= '</table>';
    return $s;
}

/* NO EM FUNCIONA

function transposaMatriu($matriu) {
    $arrayMatriuInversa = array();
    for ($i = 0; $i < $matriu; $i++) {
        for($j = 0; $j < $matriu; $j++) {
            if ($i == $j) {
                $arrayMatriuInversa[$i][$j] = "*";
            }
            else if ($i > $j) {
                $arrayMatriuInversa[$j][$i] = $matriu[$i][$j];

            }
            else if ($i < $j) {
                $arrayMatriuInversa[$j][$i] = $matriu[$i][$j];
            }
        }
    }
    return $arrayMatriuInversa;
}*/

$num = 4;
$matriu = creaMatriu($num);
echo mostraMatriu($matriu);

?>