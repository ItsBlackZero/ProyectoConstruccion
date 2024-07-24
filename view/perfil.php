<?php
session_start();
include "../config/Conexion.php";

// Verificar si el usuario está logueado
if (!isset($_SESSION['login'])) {
    header("Location: ../view/inicioSesion.php");
    exit();
}

// Obtener información del usuario logueado
$username = $_SESSION['login'];
$query = "SELECT * FROM usuarios WHERE username = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Manejar la búsqueda de amigos
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Obtener amigos del usuario con filtro de búsqueda
$query = "SELECT u.username FROM amigos a JOIN usuarios u ON a.id_friend = u.id_usuario WHERE a.id_usuario = ? AND u.username LIKE ?";
$search_param = "%" . $search . "%";
$stmt = $conexion->prepare($query);
$stmt->bind_param("is", $user['id_usuario'], $search_param);
$stmt->execute();
$friends = $stmt->get_result();

// Obtener publicaciones del usuario
$query = "SELECT f.*, u.username FROM foros f JOIN usuarios u ON f.id_usuario = u.id_usuario WHERE f.id_usuario = ? ORDER BY f.create_at DESC";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $user['id_usuario']);
$stmt->execute();
$posts = $stmt->get_result();

// Manejar el envío de comentarios
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_comment'])) {
    $id_foros = $_POST['id_foros'];
    $contenido = $_POST['contenido'];

    $query = "INSERT INTO comentarios (id_foros, id_usuario, contenido) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("iis", $id_foros, $user['id_usuario'], $contenido);
    $stmt->execute();

    header("Location: ../view/perfil.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="../assets/style/reset.css">
    <link rel="stylesheet" href="../assets/style/perfil_style.css">

</head>
<body>
    <div class="navbar">
        <a href="perfil.php">Perfil</a>
        <a href="posteo.php">Foro General</a>
        <a href="../controller/cerrarSesion.php">Cerrar sesión</a>

    </div>

    <div class="contenedorPrincipal">
        <!-- Contenedor de Usuario y Amigos -->
        <div class="contenedorUsuarioAmigos">
            <section class="contenedorPerfil">
                <h1>Perfil de <?php echo htmlspecialchars($user['username']); ?></h1>
                <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            </section>

        </div>
        
        <!-- Contenedor de Publicaciones -->
        <div class="contenedorPublicaciones">
            <h2>Mis Publicaciones</h2>
            <?php while ($post = $posts->fetch_assoc()): ?>
                <div class="contenedorPublicacion">
                    <h3><?php echo htmlspecialchars($post['titulo']); ?></h3>
                    <p><?php echo htmlspecialchars($post['contenido']); ?></p>
                    <p><i><strong>Publicado por: <?php echo htmlspecialchars($post['username']); ?></strong></i></p>

                    <h4>Comentarios</h4>
                    <?php
                    $commentQuery = "SELECT c.*, u.username FROM comentarios c JOIN usuarios u ON c.id_usuario = u.id_usuario WHERE c.id_foros = ? ORDER BY c.create_at ASC";
                    $commentStmt = $conexion->prepare($commentQuery);
                    $commentStmt->bind_param("i", $post['id_foros']);
                    $commentStmt->execute();
                    $comments = $commentStmt->get_result();
                    ?>
                    <ul>
                        <?php while ($comment = $comments->fetch_assoc()): ?>
                            <li><?php echo htmlspecialchars($comment['contenido']); ?> - <i><strong><?php echo htmlspecialchars($comment['username']); ?>, <?php echo htmlspecialchars($comment['create_at']); ?></strong></i></li>
                        <?php endwhile; ?>
                    </ul>

                    <!-- Formulario para agregar un comentario -->
                    <form action="../controller/agregarComentario.php" method="post" >
                        <input type="hidden" name="id_foros" value="<?php echo $post['id_foros']; ?>">
                        <input type="hidden" name="return_url" value="../view/perfil.php">
                        <textarea class="contenedorComentario" name="contenido" required></textarea><br>
                        <input type="submit" name="add_comment" value="Comentar" class="buttonComentar">
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
        <div class="contenedorAmigos">
                <h2>Buscar Amigos</h2>
                <form action="perfil.php" method="get">
                    <input type="text" id="search" name="search" placeholder="Buscar amigos" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Buscar</button>
                </form>
                <h2>Amigos</h2>
                <ul>
                <?php while ($friend = $friends->fetch_assoc()): ?>
                    <li><?php echo htmlspecialchars($friend['username']); ?></li>
                <?php endwhile; ?>
                </ul>
            </div>
    </div>
</body>
</html>
