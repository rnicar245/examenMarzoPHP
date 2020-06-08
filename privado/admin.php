<?php
include_once("../clases/Serie.php");
include_once("../clases/Encuesta.php");
include_once("../config/config.php");
include_once("../config/funciones.php");
session_start();

$serie = Serie::getInstancia();
$encuesta = Encuesta::getInstancia();


if(!$_SESSION['logeado'] or $_SESSION['perfil'] != "admin"){
    $_SESSION['mensaje'] = "Nada de trampas.";
    header('Location: ../login.php');
}

if(isset($_POST['cambiarEstado'])){
    $series = $serie->getAllSeries();
    $indice = "";
    foreach($_POST['cambiarEstado'] as $i=>$valor){
        $indice = $i;
    }
    $serie->edit($series[$indice]['estado'], $series[$indice]['id']);
    $_SESSION['mensaje'] = $serie->mensaje;
}

if(isset($_POST['anadirSerie'])){
    if(!file_exists("../img")){
        mkdir("../img", 0777);
    }

    if(file_exists("../img/".$_FILES['file']['name'])){
        $_SESSION['mensaje'] = "<span style=\"color:red\">El fichero ya existe.</span>";
    }else{
        move_uploaded_file($_FILES['file']['tmp_name'], "../img/".$_FILES['file']['name']);
        $serie->set(array(
                    "titulo"=>limpiarDatos($_POST['titulo']),
                    "img"=>$_FILES['file']['name'],
                    "estado"=>"habilitado"));
        $_SESSION['mensaje'] = $serie->mensaje;
    }
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
    echo "<br>Usted está logeado como ".$_SESSION['usuario'].".<br>";
    echo "<nav><ul><li><a href=\"../cerrar.php\">Logout</a></li></ul></nav>";

    echo "<h2>Media de la encuesta:</h2> ";
    for($i = 1; $i <=5 ;$i++){
        echo "<b>Pregunta ".$i.": ".$encuesta->getPuntuacionMedia($i)."/5</b><br>";
    }

    echo "<form action= ".htmlspecialchars($_SERVER["PHP_SELF"])." method= \"POST\" enctype=\"multipart/form-data\">";
    echo "<h2>Añadir series</h2> ";
    echo "Título:<br> ";
    echo "<input type=\"text\" name=\"titulo\" value=\"\" required><br><br>";
    echo "Portada (Formatos aceptados: .png, .jpg, .gif):<br><br> ";
    echo "<input type=\"FILE\" name=\"file\" accept=\".png, .jpg, .gif\" required><br><br>";
    echo "<br><input type=\"submit\" name=\"anadirSerie\" value=\"Enviar\">";
    echo "</form>";

    echo "</br><form action= ".htmlspecialchars($_SERVER["PHP_SELF"])." method= \"POST\">";
    echo"<table border=1>";
    echo "<caption>Series</caption>";
    echo "<tr><td>Título</td><td>Portada</td></tr>";
    $series = $serie->getAllSeries();
    for($i = 0; $i < count($series); $i++){
        echo "<tr>";
        echo "<td>".$series[$i]['titulo']."</td>";
        echo "<td><img width=\"100px\" heigth=\"100px\" src=\"../img/".$series[$i]['img']."\"></img></td>";
        echo "<td>".$series[$i]['estado']."</td>";
        if($series[$i]['estado'] == "habilitado"){
            echo "<td><input type=\"submit\" name=\"cambiarEstado[".$i."]\" value=\"Deshabilitar\"></td>";
        }else{
            echo "<td><input type=\"submit\" name=\"cambiarEstado[".$i."]\" value=\"Habilitar\"></td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "</form>";

    

?>

</body>
</html>