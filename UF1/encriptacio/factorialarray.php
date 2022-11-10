<?php

function factorialArray($arrayNombre) {
$factorials = array();

    if (is_int($arrayNoms) && is_array($arrayNoms)) {
        foreach ($arrayNoms as $numero) {
            for ($i = $numero - 1; $i > 1; $i--) {
                $numero = $numero * $i;
            }
            $factorials[] = $numero;
        }
        foreach ($factorials as $factorials){
            echo $factorials . "<br>";
        }
    }else{
        return false;
    }
}


$noms = array(15, 42, 12, 22, 77, "I");

factorialArray($nombres);
?>