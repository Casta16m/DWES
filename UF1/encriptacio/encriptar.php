<?php

/**
 * 1r. Separar de 3 en 3 les lletres. EX: kfh xiv roz ziu ort ghr vxr rkc roz xlw flr h
 * 2n. Capgirar el triplet de lletres. EX: hfk vix zor uiz...
 * 3è. Canviar segons l'ordre alfabetic.
 *          - Si és una c, és la tercera començant. Llavors x, és la tercera acavant.
 */

function decrypt(String $str) {
    $arrayRevers = ""; // creem una cadena buida per guardar l'array capgirat
    $strFinal = ""; // creem una cadena buida per guardar 
    $str = str_split($str, 3); //converteixo la cadena en array i separot en 3 les lletres per cada celda de l'array
    for ($i = 0;$i < count($str); $i++) {
        $arrayRevers .= strrev($str[$i]); //concatenot
    }
    $arrayRevers = str_split($arrayRevers);
    
    /**
     * ord per coneixer el valor de la lletra en codi ASCII
     * chr per passar de codi ASCII al valor/lletra normal
     * comptador ($i) per saber la posició de les lletres
     * array cada celda es un valor (una lletra)
     * Codi Ascii: 
     *      122 - lletra = Diferencia;
     *      Diferencia + 97 = lletra;
     */
    
    for ($i = 0; $i < count($arrayRevers); $i++) {
        $codiAscii = ord($arrayRevers[$i]); 
        $Diferencia = 122 - $codiAscii;
        $contraria = (97 + $Diferencia);
        $strFinal .= chr($contraria);
    }
    return $strFinal;
}

$sp = "kfhxivrozziuortghrvxrrkcrozxlwflrh";
$mr = " hv ovxozwozv vj o vfrfjvivfj h vmzvlo e hrxvhlmov oz ozx.vw z xve hv loqvn il hv lmnlg izxvwrhrvml ,hv b lh mv,rhhv mf w zrxvlrh.m";

echo decrypt($sp);
echo "<br>";
echo decrypt($mr);


//base_convert Exercici_3
?>