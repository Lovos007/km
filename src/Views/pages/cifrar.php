<?php 

use App\Controllers\LoginController;

$cifrar = new loginController;
$passwordCifrado = $cifrar ->cifrarPass('1234'); 
echo $passwordCifrado;
echo "<br>";

$info = password_get_info($passwordCifrado);

if ($info['algo']) {
    echo "La contraseña ya está cifrada con el algoritmo: " . $info['algoName'];
} else {
    echo "La contraseña está en texto plano.";
}
