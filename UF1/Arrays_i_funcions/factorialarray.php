<?php

function factorialNum($num){ 
  $factorial = 1; 
  for ($i = 1; $i <= $num; $i++){ 
    $factorial = $factorial * $i; 
  } 
  return $factorial; 
}

function factorailArray($arrayNumeros){
  $arrayNum = array();
    for ($i = 0; $i < count($arrayNumeros); $i++){
      if (is_numeric($arrayNumeros[$i])) {
        $arrayNum[$i] = factorialNum($arrayNumeros[$i]);
      } else {
        return false;
      }
    }
    return implode($arrayNum);
}

/*--------------------------------*/

$arrayNumeros = array(1, 2, 3, 4, 5);

echo factorailArray($arrayNumeros);
?>