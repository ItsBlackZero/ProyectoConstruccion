<?php
    $host ="localhost";
    $user = "root";
    $pass = "1234";

    $db ="proyecto_construccion";
    $conexion = new mysqli($host,$user,$pass,$db);

    if($conexion->connect_error){
        echo "conexion fallida";
    }
?>

