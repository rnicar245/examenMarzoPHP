<?php
include_once("../config/config.php");
include_once("../config/funciones.php");
session_start();

if(!$_SESSION['logeado'] or $_SESSION['perfil'] == "admin"){
    $_SESSION['mensaje'] = "Nada de trampas.";
    header('Location: ../login.php');
}

$fileName = "pago.txt";

header("Content-disposition: attachment; filename=$fileName");
header("Content-type: text/plain");

echo "CARTA DE PAGO\n";
echo $_SESSION['usuario']."\n";
echo "Cuota mensual por la suscripción a RedFilm.\n";
echo "Importe: 10€\n";
echo "".date("d")."/".date("m")."/".date("Y");

$_SESSION['mensaje'] = "<span style=\"color:green\">Descarga realizada con éxito.</span>";

readfile("../config/sample.txt");
exit;


header('Location: user.php');




?>