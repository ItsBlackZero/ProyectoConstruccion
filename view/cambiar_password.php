<?php require("./layouts/base.php")?>

<div class="contenedorPrincipal">
        <div class="contenedorLogin">
            <form action="../controller/cambiar_password.php" method="post">
                <h3>Recupera tu password</h3><br>
                <label for="new_password">Password:</label>
                <input type="new_password" id="new_password" name="new_password" required>
                <input hidden name="id"value="<?php echo $_GET['id'];?>">
                
                <br>
                <input type="submit" name="recuperar" value="Recuperar">
            </form>
        </div>
    </div>