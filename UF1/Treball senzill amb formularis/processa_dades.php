<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>

<body>
    <?php
    foreach ($_REQUEST as $clau => $valor) {

        echo "Clau: $clau <br/>";

        if (gettype($valor) == "array") {
            echo "Valor: ";
            for ( $i=0; $i > $valor; $i++) {
                echo "$i, ";
            }
        } else {
            echo "El valor de text és: " . $valor;
        }
        echo "<br>";
        echo "<br>";
    }
        
    ?>
</body>

</html>