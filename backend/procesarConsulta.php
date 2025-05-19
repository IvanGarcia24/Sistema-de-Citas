<?php
// Incluir la conexión a la base de datos
include('conexion.php');

// Verificar que los datos han sido enviados correctamente desde el formulario
if (isset($_POST['id_usuario'], $_POST['id_doctor'], $_POST['fecha_consulta'], $_POST['piso'], $_POST['num_consultorio'], $_POST['sintomas'], $_POST['duracion_sintomas'])) {

    // Asignar los valores recibidos a variables
    $id_usuario = $_POST['id_usuario'];
    $id_doctor = $_POST['id_doctor'];
    $fecha_consulta = $_POST['fecha_consulta']; // Fecha y hora de la consulta
    $piso = $_POST['piso'];
    $num_consultorio = $_POST['num_consultorio'];
    $sintomas = $_POST['sintomas'];
    $duracion_sintomas = $_POST['duracion_sintomas'];

    // Escapar las variables para evitar inyecciones SQL
    $id_usuario = mysqli_real_escape_string($conexion, $id_usuario);
    $id_doctor = mysqli_real_escape_string($conexion, $id_doctor);
    $fecha_consulta = mysqli_real_escape_string($conexion, $fecha_consulta);
    $piso = mysqli_real_escape_string($conexion, $piso);
    $num_consultorio = mysqli_real_escape_string($conexion, $num_consultorio);
    $sintomas = mysqli_real_escape_string($conexion, $sintomas);
    $duracion_sintomas = mysqli_real_escape_string($conexion, $duracion_sintomas);

    // Consulta SQL para insertar la cita
    $sql_insert_cita = "INSERT INTO citas (id_usuario, id_doctor, fecha_hora, piso, num_consultorio, sintomas, duracion_sintomas) 
                        VALUES ('$id_usuario', '$id_doctor', '$fecha_consulta', '$piso', '$num_consultorio', '$sintomas', '$duracion_sintomas')";

    // Ejecutar la consulta
    if (mysqli_query($conexion, $sql_insert_cita)) {
        // Redirigir a la página de citas con un mensaje de éxito
        header("Location: ../appointment.php?mensaje=cita_creada");
        exit();
    } else {
        // En caso de error, redirigir con un mensaje de error
        header("Location: ../appointment.php?mensaje=error_cita");
        exit();
    }

} else {
    // Si no se han recibido los datos correctamente, redirigir con un mensaje de error
    header("Location: ../appointment.php?mensaje=error_cita");
    exit();
}

// Cerrar la conexión a la base de datos
mysqli_close($conexion);
?>
