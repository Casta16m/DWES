<?php
$i = 12;
$float = 1.7976931348623E+308;
$cadena = "paraula";
$boleanna = true;
$boleanna2 = false;
$tipus_de_i = gettype( $i );
$tipus_de_f = gettype( $float );
$tipus_de_c = gettype( $cadena );
$tipus_de_b = gettype( $boleanna );
echo "La variable \$i conté el valor $i i és del tipus $tipus_de_i <br>", //
      "La variable \$float conté el valor $float i és del tipus $tipus_de_f <br>", 
      "La variable \$cadena conté el valor $cadena i és del tipus $tipus_de_c <br>", 
      "La variable \$boleanna conté el valor 'true' $boleanna, i \$boleanna2 conté el valor 'false' 
      (per això no es veura) $boleanna2 és del tipus $tipus_de_b";
?>