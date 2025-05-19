<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_cita'])) {
    include('conexion.php');
    
    $id_cita = $_POST['id_cita'];
    $id_usuario = $_SESSION['usuario']['id_usuario'];
    
    $sql = "UPDATE citas SET completada = 1 WHERE id_cita = ? AND id_usuario = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id_cita, $id_usuario);
    mysqli_stmt_execute($stmt);
    
    if (mysqli_stmt_affected_rows($stmt)) {
        header("Location: ../appointment.php?mensaje=cita_actualizada");
    } else {
        header("Location: ../appointment.php?mensaje=error_actualizar");
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conexion);
    exit();
} else {
    header("Location: ../appointment.php?mensaje=id_no_proporcionado");
    exit();
}
?>