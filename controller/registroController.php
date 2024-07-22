<?php

    session_start();

    require("../config/Conexion.php");
    if(isset($_POST["register"])){
        $username = $_POST["username"];
        $password = $_POST["pasword"];
        if(validarCredenciales()){ // valida que el username o correo no exista en la base
            $_SESSION["mensaje_error"] = "El correo o usuario ya existe, por favor ingrese otro";
            header("Location:../view/registroUsuario.php");
            exit();
        }
        $email = $_POST["email"];
        $sql = "INSERT INTO USUARIOS (username, email, pasword) VALUES ('$username', '$email', '$password')";
        if (mysqli_query($conexion, $sql)) {
            header("Location:../index.php");
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
        }
        mysqli_close($conexion);
    }



    // esta funcion valida que el username y el correo esten registrados en la base de datos
    function validarCredenciales(){
        require("../config/Conexion.php");
        $email =$_POST["email"];
        $username =$_POST["username"];
        $sql1 = "SELECT email FROM usuarios WHERE email='$email'";
        $result1 = mysqli_query($conexion,$sql1);

        $sql2 = "SELECT username FROM usuarios WHERE username='$username'";
        $result2 = mysqli_query($conexion,$sql2);
        //aqui valida que exista mas de una columna de resultado
        if(mysqli_num_rows($result1) > 0 || mysqli_num_rows($result2) > 0){
            return true;
        }else{
            return false;
        }


    }
?>