<?php
$d = new DateTime();
$tipus_de_d = gettype( $d );
// saber quina classe d'objecta és $d
$classe_de_d = get_class( $d );
echo "La variable \$d conté el valor " . $d->format( "d/m/Y") . " i és del tipus $classe_de_d";
?>