<?php require("./layouts/base.php")?>

<div class="contenedorPrincipal">
        <div class="contenedorLogin">
            <form action="../controller/recuperarPasswordController.php" method="post">
                <h3>Por favor ingresa tu correo registrado</h3><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <br>
                <input type="submit" name="recuperar" value="Recuperar">
            </form>
        </div>
    </div>