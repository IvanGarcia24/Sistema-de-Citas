<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_cita = $_GET['id'];
    $id_usuario = $_SESSION['usuario']['id_usuario'];

    include('conexion.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validar y obtener los nuevos datos de la cita
        $nueva_fecha_hora = $_POST["fecha_consulta"];
        $nuevo_id_doctor = $_POST["id_doctor"];
        $nuevo_piso = $_POST["piso"];
        $nuevo_num_consultorio = $_POST["num_consultorio"];
        $nuevos_sintomas = $_POST["sintomas"];
        $nueva_duracion_sintomas = $_POST["duracion_sintomas"];
        $completada = isset($_POST["completada"]) ? 1 : 0;

        // Actualizar la cita en la base de datos
        $sql = "UPDATE citas SET 
                fecha_hora = ?, 
                id_doctor = ?, 
                piso = ?, 
                num_consultorio = ?, 
                sintomas = ?, 
                duracion_sintomas = ?,
                completada = ?
                WHERE id_cita = ? AND id_usuario = ?";
        
        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "sissssiii", 
            $nueva_fecha_hora, 
            $nuevo_id_doctor, 
            $nuevo_piso, 
            $nuevo_num_consultorio, 
            $nuevos_sintomas, 
            $nueva_duracion_sintomas,
            $completada,
            $id_cita,
            $id_usuario);
        
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt)) {
            header("Location: ../appointment.php?mensaje=cita_actualizada");
        } else {
            header("Location: ../appointment.php?mensaje=error_actualizar");
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conexion);
        exit();
    }

    // Obtener los datos actuales de la cita
    $sql = "SELECT * FROM citas WHERE id_cita = ? AND id_usuario = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id_cita, $id_usuario);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $cita = mysqli_fetch_assoc($resultado);

    if (!$cita) {
        header("Location: ../appointment.php?mensaje=cita_no_encontrada");
        exit();
    }

    // Obtener lista de doctores
    $sql_doctor = "SELECT d.id_doctor, d.nombre, d.apellidos, e.nombre as especialidad 
                   FROM doctor d JOIN especialidad e ON d.id_especialidad = e.id_especialidad";
    $result_doctores = mysqli_query($conexion, $sql_doctor);

    $doctores = [];
    while ($doctor = mysqli_fetch_assoc($result_doctores)) {
        $doctores[] = $doctor;
    }

    mysqli_close($conexion);
} else {
    header("Location: ../appointment.php?mensaje=id_no_proporcionado");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cita Médica</title>
    <link rel="stylesheet" href="../assets/style3.css">
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Nothing+You+Could+Do&family=Montserrat:wght@300;500;700&family=Roboto+Condensed:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1>Editar Cita Médica</h1>
        
        <?php
        if (isset($_GET['mensaje'])) {
            $mensajes = [
                'error_datos' => ['texto' => 'Error en los datos proporcionados.', 'clase' => 'error-message']
            ];
            
            if (isset($mensajes[$_GET['mensaje']])) {
                echo "<p class='{$mensajes[$_GET['mensaje']]['clase']}'>{$mensajes[$_GET['mensaje']]['texto']}</p>";
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id_cita; ?>" method="post">
            <div class="form-section">
                <h3><i class="fas fa-info-circle"></i> Información de la Consulta</h3>
                
                <label for="fecha_consulta"><i class="far fa-calendar-alt"></i> Fecha y Hora</label>
                <input type="datetime-local" id="fecha_consulta" name="fecha_consulta" 
                       value="<?php echo date('Y-m-d\TH:i', strtotime($cita['fecha_hora'])); ?>" required>
                
                <label for="id_doctor"><i class="fas fa-user-md"></i> Seleccionar Doctor</label>
                <select name="id_doctor" id="id_doctor" required>
                    <option value="">Seleccione un doctor</option>
                    <?php foreach ($doctores as $doctor): ?>
                        <option value="<?= $doctor['id_doctor'] ?>" 
                            <?= $doctor['id_doctor'] == $cita['id_doctor'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($doctor['nombre']) ?> <?= htmlspecialchars($doctor['apellidos']) ?> - <?= htmlspecialchars($doctor['especialidad']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="piso"><i class="fas fa-building"></i> Piso</label>
                <input type="text" id="piso" name="piso" value="<?= htmlspecialchars($cita['piso']) ?>" required>
                
                <label for="num_consultorio"><i class="fas fa-door-open"></i> Número de Consultorio</label>
                <input type="text" id="num_consultorio" name="num_consultorio" value="<?= htmlspecialchars($cita['num_consultorio']) ?>" required>
            </div>

            <div class="form-section">
                <h3><i class="fas fa-notes-medical"></i> Detalles Clínicos</h3>
                <label for="sintomas"><i class="fas fa-comment-medical"></i> Síntomas Principales</label>
                <textarea id="sintomas" name="sintomas" rows="4" required><?= htmlspecialchars($cita['sintomas']) ?></textarea>
                
                <label for="duracion_sintomas"><i class="fas fa-clock"></i> Duración de los Síntomas</label>
                <input type="text" id="duracion_sintomas" name="duracion_sintomas" 
                       value="<?= htmlspecialchars($cita['duracion_sintomas']) ?>">
                
                <div class="checkbox-container">
                    <input type="checkbox" id="completada" name="completada" value="1" <?= $cita['completada'] ? 'checked' : '' ?>>
                    <label for="completada">Marcar como completada</label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-guardar"><i class="fas fa-save"></i> Guardar Cambios</button>
                <a href="../appointment.php" class="btn-cancelar"><i class="fas fa-times"></i> Cancelar</a>
            </div>
        </form>
    </div>

    <form action="../backend/logout.php" method="post">
        <button type="submit" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Cerrar sesión
        </button>
    </form>
</body>
</html>