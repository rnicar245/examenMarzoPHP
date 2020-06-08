<?php
include_once("clases/Usuario.php");
include_once("config/config.php");
include_once("config/funciones.php");
session_start();

$usuarioTemp = "";

if (!isset($_SESSION['mensaje'])){
    $_SESSION['mensaje'] = "";
    $_SESSION['logeado'] = false;
    $_SESSION['usuario'] = "invitado";
}

if(isset($_POST['enviar'])){
    $usuarioTemp = limpiarDatos($_POST["usuario"]);
    $usuario = Usuario::getInstancia();

    if($usuario->getUsuarioCorrecto($usuarioTemp, limpiarDatos($_POST["password"]))){
        $_SESSION['mensaje'] = $usuario->mensaje;
        $_SESSION['logeado'] = true;
        $_SESSION['usuario'] = $usuarioTemp;
        $_SESSION['id'] = $usuario->getId($usuarioTemp);
        $_SESSION['perfil'] = $usuario->getPerfil($_SESSION['id']);
        if($_SESSION['perfil'] == "admin"){
            header('Location: privado/admin.php');
        }else{
            header('Location: privado/user.php');
        }
    }
    $_SESSION['mensaje'] = $usuario->mensaje;
}

?>
<html>
<head>
    <meta charset="utf-8">
    <title>Seriestv</title>
</head>
<body>

<?php
    echo "<p>".$_SESSION['mensaje']."</p>";
    echo "<form action= ".htmlspecialchars($_SERVER["PHP_SELF"])." method= \"POST\" enctype=\"multipart/form-data\">";
    echo "<h2>Login</h2>";
    echo "Usuario:<br> ";
    echo "<input type=\"text\" name=\"usuario\" value=\"".$usuarioTemp."\"><br>";
    echo "Contraseña:<br> ";
    echo "<input type=\"text\" name=\"password\"><br>";
    echo "<br><input type=\"submit\" name=\"enviar\" value=\"Enviar\">";
    echo "</form>";
?>

</body>
</html>