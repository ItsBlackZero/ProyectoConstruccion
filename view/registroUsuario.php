<?php require("./layouts/base.php");
    session_start();
?>

<body>
    <div class="contenedorPrincipal">
        <div class="contenedorLogin">
            <?php
                if(isset($_SESSION["mensaje_error"])){
                    echo '<div class="error">' . $_SESSION['mensaje_error'] . '</div>';
                    unset($_SESSION['mensaje_error']);
                }

            ?>
            <form action="../controller/registroController.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="pasword" required>
                <br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <br>
                <input type="submit" name="register" value="Registar">
            </form>
        </div>
    </div>
</body>
</html>

