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

// Cambiar el límite de publicaciones por página a 3
$limit = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

$query = "SELECT f.*, u.username FROM foros f JOIN usuarios u ON f.id_usuario = u.id_usuario ORDER BY f.create_at DESC LIMIT ?, ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("ii", $start, $limit);
$stmt->execute();
$posts = $stmt->get_result();

// Contar el número total de publicaciones
$query = "SELECT COUNT(*) AS total FROM foros";
$result = $conexion->query($query);
$total_posts = $result->fetch_assoc()['total'];
$total_pages = ceil($total_posts / $limit);

// Manejar el envío de publicaciones
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_post'])) {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];

    $query = "INSERT INTO foros (id_usuario, titulo, contenido) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("iss", $user['id_usuario'], $titulo, $contenido);
    $stmt->execute();

    header("Location: posteo.php");
    exit();
}

// Manejar el envío de comentarios
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_comment'])) {
    $id_foros = $_POST['id_foros'];
    $contenido = $_POST['contenido'];

    $query = "INSERT INTO comentarios (id_foros, id_usuario, contenido) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("iis", $id_foros, $user['id_usuario'], $contenido);
    $stmt->execute();

    header("Location: posteo.php");
    exit();
}

// Buscar usuarios
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM usuarios WHERE username LIKE ? AND id_usuario != ?";
$searchTerm = "%{$search}%";
$stmt = $conexion->prepare($query);
$stmt->bind_param("si", $searchTerm, $user['id_usuario']);
$stmt->execute();
$usuarios = $stmt->get_result();

// Contar el número total de publicaciones
$query = "SELECT COUNT(*) AS total FROM foros";
$result = $conexion->query($query);
$total_posts = $result->fetch_assoc()['total'];
$total_pages = ceil($total_posts / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foro General</title>
    <link rel="stylesheet" href="../assets/perfil_post.css">
    <link rel="stylesheet" href="../assets/style/style.css">
    <style>
        /* Estilo para la barra de navegación */
        .navbar {
            background-color: #333;
            overflow: hidden;
            position: fixed; /* Fija la barra de navegación */
            top: 0;
            width: 100%;
            z-index: 1000; /* Asegura que esté siempre en el frente */
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        /* Añade margen superior para el contenido principal para evitar superposición con la barra de navegación */
        .contenedorPrincipal {
            display: flex;
            flex-direction: row;
            height: calc(100vh - 50px); /* Ajuste según la altura de la barra de navegación */
            margin-top: 50px; /* Espacio para la barra de navegación fija */
        }

        /* Estilo para la sección de publicaciones */
        .publicaciones {
            flex: 2;
            overflow-y: auto;
            padding: 10px;
            border-right: 1px solid #ccc;
            height: 100%; /* Asegura que ocupe toda la altura disponible */
        }

        /* Estilo para la sección de usuarios y formulario */
        .sidebar {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            padding: 10px;
            height: 100%; /* Asegura que ocupe toda la altura disponible */
        }

        /* Estilo para formularios y secciones */
        form, .section {
            margin-bottom: 20px;
        }

        .post {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }

        /* Responsivo: ajusta el diseño en pantallas pequeñas */
        @media (max-width: 768px) {
            .contenedorPrincipal {
                flex-direction: column; /* Cambia a diseño de columna en pantallas pequeñas */
            }

            .publicaciones, .sidebar {
                flex: 1;
                overflow-y: auto;
            }

            .publicaciones {
                border-right: none;
                border-bottom: 1px solid #ccc;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="perfil.php">Perfil</a>
        <a href="posteo.php">Foro General</a>
        <a href="../controller/cerrarSesion.php">Cerrar sesión</a>
    </div>
    
    <div class="contenedorPrincipal">
        <div class="sidebar">
            <!-- Información del usuario y formulario para realizar una publicación -->
            <div class="section">
                <h2>Usuario Logeado</h2>
                <!-- Aquí se puede mostrar información del usuario logeado -->
            </div>

            <form action="posteo.php" method="post">
                <h2>Realizar una Publicación</h2>
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="titulo" required>
                <br>
                <label for="contenido">Contenido:</label>
                <textarea id="contenido" name="contenido" required></textarea>
                <br>
                <input type="submit" name="add_post" value="Publicar">
            </form>

            <div class="section">
                <h2>Buscar Usuarios</h2>
                <form action="posteo.php" method="get">
                    <input type="text" id="search" name="search" placeholder="Buscar usuarios" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Buscar</button>
                </form>
            </div>

            <div class="section">
                <h2>Usuarios Registrados</h2>
                <ul>
                    <?php while ($userRow = $usuarios->fetch_assoc()): ?>
                        <li>
                            <?php echo htmlspecialchars($userRow['username']); ?>
                            <?php
                            // Verificar si ya son amigos
                            $query = "SELECT * FROM amigos WHERE (id_usuario = ? AND id_friend = ?) OR (id_usuario = ? AND id_friend = ?)";
                            $stmt = $conexion->prepare($query);
                            $stmt->bind_param("iiii", $user['id_usuario'], $userRow['id_usuario'], $userRow['id_usuario'], $user['id_usuario']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $is_friend = $result->num_rows > 0;
                            ?>
                            <?php if ($userRow['id_usuario'] != $user['id_usuario']): ?>
                                <form action="../controller/agregarAmigo.php" method="post" style="display:inline;">
                                <input type="hidden" name="id_friend" value="<?php echo htmlspecialchars($userRow['id_usuario']); ?>">
                                <input type="submit" value="<?php echo $is_friend ? 'Ya son amigos' : 'Agregar amigo'; ?>" <?php echo $is_friend ? 'disabled' : ''; ?>>
                            </form>

                            <?php endif; ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </div>

        <div class="publicaciones">
            <h2>Publicaciones Recientes</h2>
            <?php while ($post = $posts->fetch_assoc()): ?>
                <div class="post">
                    <h3><?php echo htmlspecialchars($post['titulo']); ?></h3>
                    <p><?php echo htmlspecialchars($post['contenido']); ?></p>
                    <p><i>Publicado por: <?php echo htmlspecialchars($post['username']); ?></i></p>

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
                            <li><?php echo htmlspecialchars($comment['contenido']); ?> - <i><?php echo htmlspecialchars($comment['username']); ?>, <?php echo htmlspecialchars($comment['create_at']); ?></i></li>
                        <?php endwhile; ?>
                    </ul>

                    <!-- Formulario para agregar un comentario -->
                    <form action="posteo.php" method="post">
                        <input type="hidden" name="id_foros" value="<?php echo $post['id_foros']; ?>">
                        <textarea name="contenido" required></textarea>
                        <input type="submit" name="add_comment" value="Comentar">
                    </form>
                </div>
            <?php endwhile; ?>

            <!-- Paginación -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>"><< Anterior</a>
                <?php endif; ?>
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>">Siguiente >></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
