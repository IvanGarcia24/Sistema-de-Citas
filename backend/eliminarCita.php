<?php
session_start();
include('conexion.php');

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$id_cita = $_POST['id_cita'];

// Eliminar la cita
$sql_eliminar = "DELETE FROM citas WHERE id_cita = $id_cita";
if (mysqli_query($conexion, $sql_eliminar)) {
    header("Location: ../appointment.php?mensaje=cita_eliminada");
    exit();
} else {
    header("Location: ../appointment.php?mensaje=error_eliminar");
    exit();
}
?>
