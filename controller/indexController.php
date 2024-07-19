<?php

    if(isset($_POST['login'])){
        $username = $_POST['username'];
        $pasword = $_POST['pasword'];
        include "../config/Conexion.php";


        $sql ="SELECT * FROM usuarios where username='".$username."'AND pasword='".$pasword."'";
        $result = $conexion ->query($sql);

        if($result->num_rows>0){
            $user = $result->fetch_assoc();
            session_start();
            $_SESSION["login"]= $user['username'];
            header("Location:../view/perfil.php");
            exit();
        }
        header("Location:../index.php");
    }
?>