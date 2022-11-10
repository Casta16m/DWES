<?php
//Aquí escriurem la cadena de 
$clave  = 'Hola bona tarda';
//Metoda d'encriptació
$method = 'aes-256-cbc';
// Amb "base64" es refereix (en aquest cas) per decodificar les dades codificades en base64
$iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
 /*
 Encripta el cuntingut de la variable, enviada amb el parametra
  */
 $encriptar = function ($valor) use ($method, $clave, $iv) {
     return openssl_encrypt ($valor, $method, $clave, false, $iv);
 };
 /*
 Desencripta el text rebut
 */
 $desencriptar = function ($valor) use ($method, $clave, $iv) {
     $encrypted_data = base64_decode($valor);
     return openssl_decrypt($valor, $method, $clave, false, $iv);
 };
 /*
 Genera un valor per IV
 */
 $getIV = function () use ($method) {
     return base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length($method)));
 };
?>