<?php
include "mcript.php"; // Amb aquest "include" inclueixo i evalut l'arxiu especificat així agafut les funcions ja fetes a l'altre arxiu
// La dada que encriptarem
$dato = "Esta es información importante";
//Amb la funció de sota estem encriptant l'informacio:
$dato_encriptado = $encriptar($dato);
//I amb aquesta desencripta informacio:
$dato_desencriptado = $desencriptar($dato_encriptado);
echo 'Dato encriptado: '. $dato_encriptado . '<br>';
echo 'Dato desencriptado: '. $dato_desencriptado . '<br>';
echo "IV generado: " . $getIV();
?>