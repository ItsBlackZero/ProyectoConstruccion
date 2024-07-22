<?php
session_start();
include "../config/Conexion.php";

if (!isset($_SESSION['login'])) {
    header("Location: ../view/inicioSesion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_comment'])) {
    $id_foros = $_POST['id_foros'];
    $contenido = $_POST['contenido'];
    $username = $_SESSION['login'];
    $return_url = $_POST['return_url'];

    // Obtener ID del usuario
    $query = "SELECT id_usuario FROM usuarios WHERE username = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    // Insertar el comentario
    $query = "INSERT INTO comentarios (id_foros, id_usuario, contenido) VALUES (?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("iis", $id_foros, $user['id_usuario'], $contenido);
    $stmt->execute();

    // Redirigir a la página original
    header("Location: $return_url");
    exit();
}
?>
