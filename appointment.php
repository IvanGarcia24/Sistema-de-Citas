<?php

    //verificar sesion y conexion a la BD
    session_start();

    if (!isset($_SESSION['usuario'])) {
        header("Location: index.php");
        exit();
    }

    $nombre_usuario = $_SESSION['usuario']['nombre'] . ' ' . $_SESSION['usuario']['apellidos'];
    $id_usuario = $_SESSION['usuario']['id_usuario'];

    include('backend/conexion.php');

    //obtener nombre de los dosctores y citas de usuario

    $sql_doctor = "SELECT d.id_doctor, d.nombre, d.apellidos, e.nombre as especialidad 
                FROM doctor d JOIN especialidad e ON d.id_especialidad = e.id_especialidad";
    $result_doctores = mysqli_query($conexion, $sql_doctor);

    $doctores = [];
    while ($doctor = mysqli_fetch_assoc($result_doctores)) {
        $doctores[] = $doctor;
    }

    $sql_citas = "SELECT * FROM citas WHERE id_usuario = $id_usuario";
    $resultado_citas = mysqli_query($conexion, $sql_citas);
?>

<!--seccion de HTML-->

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Citas Médicas</title>
    <link rel="stylesheet" href="assets/style2.css">
    <link href="https://fonts.googleapis.com/css2?family=Delius&family=Nothing+You+Could+Do&family=Montserrat:wght@300;500;700&family=Roboto+Condensed:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- encabezado de la pagina -->
        <div class="welcome-header">
            <h1>Mis Citas Médicas</h1>
            <div class="welcome-name">Bienvenido, <?php echo htmlspecialchars($nombre_usuario); ?></div>
        </div>
        <!-- mensajes de notificaciones segun al crear cita, editar o eliminar-->
        <?php
        if (isset($_GET['mensaje'])) {
            $mensajes = [
                'cita_creada' => ['texto' => '¡Cita creada exitosamente!', 'clase' => 'success-message'],
                'error_cita' => ['texto' => 'Hubo un error al crear la cita. Intenta de nuevo.', 'clase' => 'error-message'],
                'cita_eliminada' => ['texto' => '¡Cita eliminada exitosamente!', 'clase' => 'success-message'],
                'error_eliminar' => ['texto' => 'Hubo un error al eliminar la cita. Intenta de nuevo.', 'clase' => 'error-message'],
                'cita_actualizada' => ['texto' => '¡Cita actualizada exitosamente!', 'clase' => 'success-message notification'],
                'error_actualizar' => ['texto' => 'Hubo un error al actualizar la cita. Intenta de nuevo.', 'clase' => 'error-message notification'],
                'cita_no_encontrada' => ['texto' => 'La cita no fue encontrada.', 'clase' => 'error-message'],
                'id_no_proporcionado' => ['texto' => 'No se proporcionó un ID de cita válido.', 'clase' => 'error-message']
            ];
            
            if (isset($mensajes[$_GET['mensaje']])) {
                echo "<div class='{$mensajes[$_GET['mensaje']]['clase']}'>{$mensajes[$_GET['mensaje']]['texto']}</div>";
            }
        }
        ?>
        <!--boton de crear una nueva cita-->
        <div class="action-buttons">
            <button class="nueva-cita-btn" onclick="openModal()">
                <i class="fas fa-calendar-plus"></i> Nueva Cita
            </button>
        </div>

        <!--citas del usuario-->
        <div class="citas-list">
        <?php
        if (mysqli_num_rows($resultado_citas) > 0) {
            while ($cita = mysqli_fetch_assoc($resultado_citas)) {
                $sql_doctor = "SELECT d.nombre AS doctor_nombre, d.apellidos AS doctor_apellidos, e.nombre AS especialidad_nombre 
                    FROM doctor d 
                    JOIN especialidad e ON d.id_especialidad = e.id_especialidad 
                    WHERE d.id_doctor = " . $cita['id_doctor'];
                $result_doctor = mysqli_query($conexion, $sql_doctor);
                $doctor = mysqli_fetch_assoc($result_doctor);

                echo "<div class='cita" . ($cita['completada'] ? ' completada' : '') . "'>";
                
                if ($cita['completada']) {
                    echo "<span class='cita-status'><i class='fas fa-check-circle'></i> COMPLETADA</span>";
                }
                
                echo "<h2><i class='fas fa-user-md'></i> Consulta con Dr. " . htmlspecialchars($doctor['doctor_nombre']) . " " . htmlspecialchars($doctor['doctor_apellidos']) . "</h2>";
                
                echo "<div class='cita-detail'><i class='fas fa-stethoscope'></i> <span>Especialidad: " . htmlspecialchars($doctor['especialidad_nombre']) . "</span></div>";
                echo "<div class='cita-detail'><i class='far fa-calendar-alt'></i> <span>Fecha: " . date('d/m/Y', strtotime($cita['fecha_hora'])) . "</span></div>";
                echo "<div class='cita-detail'><i class='far fa-clock'></i> <span>Hora: " . date('h:i A', strtotime($cita['fecha_hora'])) . "</span></div>";
                echo "<div class='cita-detail'><i class='fas fa-map-marker-alt'></i> <span>Ubicación: Piso " . htmlspecialchars($cita['piso']) . " - Consultorio " . htmlspecialchars($cita['num_consultorio']) . "</span></div>";
                
                echo "<div class='cita-symptoms'>";
                echo "<h3><i class='fas fa-notes-medical'></i> Síntomas:</h3>";
                echo "<p>" . htmlspecialchars($cita['sintomas']) . "</p>";
                echo "<p class='duration'><i class='fas fa-hourglass-half'></i> Duración: " . htmlspecialchars($cita['duracion_sintomas']) . "</p>";
                echo "</div>";

                //eliminar cita o editar -botones
                echo "<div class='cita-actions'>";
                echo "<form action='backend/eliminarCita.php' method='post'>";
                echo "<input type='hidden' name='id_cita' value='" . $cita['id_cita'] . "'>";
                echo "<button type='submit' class='btn-eliminar'><i class='fas fa-trash-alt'></i> Eliminar</button>";
                echo "</form>";
                echo "<a href='backend/editarCita.php?id=" . $cita['id_cita'] . "' class='btn-editar'><i class='fas fa-edit'></i> Editar</a>";
                
                //marcar como cita completada -boton
                if (!$cita['completada']) {
                    echo "<form action='backend/marcarCompletada.php' method='post'>";
                    echo "<input type='hidden' name='id_cita' value='" . $cita['id_cita'] . "'>";
                    echo "<button type='submit' class='btn-completar'><i class='fas fa-check'></i> Marcar como Completada</button>";
                    echo "</form>";
                }
                echo "</div>";
                
                echo "</div>";
            }
        } else {
            echo "<div class='no-citas'>";
            echo "<i class='far fa-calendar-times'></i>";
            echo "<p>No tienes citas agendadas actualmente</p>";
            echo "</div>";
        }

        mysqli_close($conexion);
        ?>
        </div>
    </div>

    <!-- Botón de cerrar sesión flotante -->
    <form action="backend/logout.php" method="post">
        <button type="submit" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Cerrar sesión
        </button>
    </form>

    <!-- modal para una nueva cita  -->
    <div id="consultaModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2><i class="fas fa-calendar-plus"></i> Solicitar Consulta Médica</h2>
            <form id="consultaForm" method="post" action="backend/procesarConsulta.php">
                <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">

                <div class="form-section">
                    <h3><i class="fas fa-info-circle"></i> Información de la Consulta</h3>
                    
                    <!-- elegir la fecha de la consulta-->
                    <div class="form-group">
                        <label for="fecha_consulta"><i class="far fa-calendar-alt"></i> Fecha y Hora</label>
                        <input type="datetime-local" id="fecha_consulta" name="fecha_consulta" required>
                    </div>
                    
                    <!--seleccionar el doctor junto con su especialidad -->
                    <div class="form-group">
                        <label for="id_doctor"><i class="fas fa-user-md"></i> Seleccionar Doctor</label>
                        <select name="id_doctor" id="id_doctor" required>
                            <option value="">Seleccione un doctor</option>
                            <?php foreach ($doctores as $doctor): ?>
                                <option value="<?= $doctor['id_doctor'] ?>">
                                    <?= htmlspecialchars($doctor['nombre']) ?> <?= htmlspecialchars($doctor['apellidos']) ?> - <?= htmlspecialchars($doctor['especialidad']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- indicar el piso donde se encuentra el consultorio -->
                    <div class="form-group">
                        <label for="piso"><i class="fas fa-building"></i> Piso</label>
                        <input type="text" id="piso" name="piso" placeholder="Ej. 3er piso" required>
                    </div>

                    <!-- indicar el numero del consultorio -->
                    <div class="form-group">
                        <label for="num_consultorio"><i class="fas fa-door-open"></i> Número de Consultorio</label>
                        <input type="text" id="num_consultorio" name="num_consultorio" placeholder="Ej. 305" required>
                    </div>
                </div>


                <!-- proporcionar detalles clinicos-->
                <div class="form-section">
                    <h3><i class="fas fa-notes-medical"></i> Detalles Clínicos</h3>

                    <!--sintomas principales-->
                    <div class="form-group">
                        <label for="sintomas"><i class="fas fa-comment-medical"></i> Síntomas Principales</label>
                        <textarea id="sintomas" name="sintomas" rows="4" required placeholder="Describa sus síntomas..."></textarea>
                    </div>
                    
                    <!--tiempo de los sintomas-->
                    <div class="form-group">
                        <label for="duracion_sintomas"><i class="fas fa-clock"></i> Duración de los Síntomas</label>
                        <input type="text" id="duracion_sintomas" name="duracion_sintomas" placeholder="Ej. 3 días, 2 semanas, etc.">
                    </div>
                </div>

                <!--crear cita-->
                <button type="submit" class="modal-btn"><i class="fas fa-paper-plane"></i> Solicitar Consulta</button>
            </form>
        </div>
    </div>

    <script>
        //abrir el modal
        function openModal() {
        document.getElementById('consultaModal').style.display = 'flex';
        // establecer fecha mínima como hoy
        const today = new Date().toISOString().slice(0, 16);
        document.getElementById('fecha_consulta').min = today;
        // desplazarse al inicio del modal
        document.getElementById('consultaModal').scrollTop = 0;
        }

        function closeModal() {
            document.getElementById('consultaModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                closeModal();
            }
        }

        // cerrar modal con tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // cerrar notificaciones automáticamente
        document.addEventListener('DOMContentLoaded', function() {
            const notifications = document.querySelectorAll('.notification');
            
            notifications.forEach(notification => {
                setTimeout(() => {
                    notification.style.animation = 'fadeOut 0.5s forwards';
                    setTimeout(() => {
                        notification.remove();
                    }, 500);
                }, 3000); // 3 segundos
            });
            
            // también se puede cerrar al hacer clic
            notifications.forEach(notification => {
                notification.addEventListener('click', function() {
                    this.style.animation = 'fadeOut 0.5s forwards';
                    setTimeout(() => {
                        this.remove();
                    }, 500);
                });
            });
        });
    </script>
    
    
</body>
</html>