

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/style/style.css">
</head>
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
                
                <input type="submit" name="login" value="Login">
            </form>
        </div>
    </div>
</body>
</html>

