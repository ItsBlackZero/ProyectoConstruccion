<?php
    require_once("../config/Conexion.php");
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    require("../vendor/autoload.php");
    if(isset($_POST["recuperar"])){
        $email = $_POST["email"];

        $sql = "SELECT * FROM usuarios WHERE email='$email'";
        $result = $conexion->query($sql);
        $row = $result->fetch_assoc();
        if($result->num_rows>0){

            $mail = new PHPMailer(true);

            try {
                //Server settings
                  //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'yoangel1221@gmail.com';                     //SMTP username
                $mail->Password   = 'hgnl qqgb pwdm aunm';                               //SMTP password         //Enable implicit TLS encryption
                $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('yoangel1221@gmail.com', 'Mailer');
                $mail->addAddress('yoangel1221@gmail.com', 'Joe User');     //Add a recipient             //Name is optional

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'Recuperacion de password';
                $mail->Body    = 'Hola, este es un correo generado automaticamente para la recuperacion
                                    de password de tu cuenta. Por favor, visita la pagina <a href="localhost/PROYECTO_CONSTRUCCION/view/cambiar_password.php?id='.$row['id_usuario'].'">Recuperacion</a>';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
                echo 'Message has been sent';
                header("Location:../view/inicioSesion.php?message=ok");
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    header("Location:../view/inicioSesion.php?message=error");
}

        }else{
            header("Location:../view/inicioSesion.php?message=not_found");
        }
    }


?>