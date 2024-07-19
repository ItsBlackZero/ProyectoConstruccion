<?php
    session_start();
    if(!isset($_SESSION['usuario'])){
        header("Location:./view/inicioSesion.php");
        exit();
    }
    
    header("Location:./view/perfil.php");


?>