
<?php require("./layouts/base.php")?>
<body>
    <div class="contenedorPrincipal">
        <div class="contenedorLogin">
            <form action="../controller/indexController.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="pasword" required>
                <br>
                <a href="recuperarPassword.php">Olvidaste tu clave?</a>
                <a href="registroUsuario.php">Registro</a>
                <input type="submit" name="login" value="Login">
                

                <?php

                if(isset($_GET["message"])){
                    ?>
                    <div class="alert alert-primary" role="alert">
                    <?php
                        switch ($_GET["message"]){
                            case 'ok':
                                echo 'Por favor revisa tu correo';
                                break;
                            case 'success_password':
                                echo "inicia sesion con tu nuevo password";
                            

                        }
                    ?>
                    </div>
                    <?php
                }

                ?>
                
            </form>
        </div>
    </div>
</body>
</html>

