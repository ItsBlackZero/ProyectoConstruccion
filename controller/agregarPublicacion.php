<?php
session_start();
include '../config/Conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../view/inicioSesion.php");
    exit();
}

$titulo = $_POST['titulo'];
$contenido = $_POST['contenido'];
$id_usuario = $_SESSION['id_usuario'];

$sql = "INSERT INTO foros (titulo, contenido, id_usuario) VALUES ('$titulo', '$contenido', '$id_usuario')";
if ($conexion->query($sql) === TRUE) {
    header("Location: ../view/posteo.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conexion->error;
}
?>
