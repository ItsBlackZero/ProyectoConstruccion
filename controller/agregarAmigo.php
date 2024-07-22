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

// Verificar si se ha enviado el formulario y el campo `id_friend` está presente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_friend'])) {
    $id_friend = $_POST['id_friend'];

    // Verificar si ya son amigos
    $query = "SELECT * FROM amigos WHERE (id_usuario = ? AND id_friend = ?) OR (id_usuario = ? AND id_friend = ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("iiii", $user['id_usuario'], $id_friend, $id_friend, $user['id_usuario']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Agregar como amigo
        $query = "INSERT INTO amigos (id_usuario, id_friend) VALUES (?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("ii", $user['id_usuario'], $id_friend);
        $stmt->execute();
    }
}

// Redirigir de vuelta a la página de posteo
header("Location: ../view/posteo.php");
exit();
?>
