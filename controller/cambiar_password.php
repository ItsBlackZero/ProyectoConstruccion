<?php

    require_once("../config/Conexion.php");
    $id = $_POST["id"];
    $password = $_POST["new_password"];

    $sql = "UPDATE usuarios set pasword='$password' where id_usuario='$id'";
    $conexion->query($sql);

    header("Location:../view/inicioSesion.php?message=success_password")

?>