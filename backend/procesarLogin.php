<?php
// activar errores (solo en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// headers para desarrollo
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

session_start();
require_once 'conexion.php';

try {
    // configuración de seguridad
    $max_intentos = 5;
    $tiempo_bloqueo = 60;

    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception("Método no permitido");
    }

    $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    
    $correo = trim($data['correo'] ?? '');
    $password = trim($data['password'] ?? '');

    // validación
    if (empty($correo) || empty($password)) {
        throw new Exception("Por favor complete todos los campos");
    }

    // verificar bloqueo de sesiones 
    if (isset($_SESSION['intentos_fallidos']) && $_SESSION['intentos_fallidos'] >= $max_intentos) {
        if (!isset($_SESSION['tiempo_bloqueo'])) {
            $_SESSION['tiempo_bloqueo'] = time();
            throw new Exception("Demasiados intentos fallidos. Por favor espere 1 minuto.");
        } else {
            $tiempo_transcurrido = time() - $_SESSION['tiempo_bloqueo'];
            if ($tiempo_transcurrido < $tiempo_bloqueo) {
                $tiempo_restante = $tiempo_bloqueo - $tiempo_transcurrido;
                throw new Exception("Demasiados intentos fallidos. Por favor espere $tiempo_restante segundos.");
            } else {
                unset($_SESSION['intentos_fallidos'], $_SESSION['tiempo_bloqueo']);
            }
        }
    }

    // consulta preparada
    $sql = "SELECT id_usuario, nombre, apellidos, correo, password FROM usuario WHERE correo = ?";
    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
    }

    $stmt->bind_param("s", $correo);
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }

    $result = $stmt->get_result();

    if ($result->num_rows !== 1) {
        $_SESSION['intentos_fallidos'] = ($_SESSION['intentos_fallidos'] ?? 0) + 1;
        throw new Exception("Credenciales incorrectas");
    }

    $usuario = $result->fetch_assoc();

    if (!password_verify($password, $usuario['password'])) {
        $_SESSION['intentos_fallidos'] = ($_SESSION['intentos_fallidos'] ?? 0) + 1;
        throw new Exception("Credenciales incorrectas");
    }

    // ingreso de login exitoso
    unset($_SESSION['intentos_fallidos'], $_SESSION['tiempo_bloqueo']);
    
    $_SESSION['usuario'] = [
        'id_usuario' => $usuario['id_usuario'],
        'nombre' => $usuario['nombre'],
        'apellidos' => $usuario['apellidos'],
        'correo' => $usuario['correo']
    ];

    echo json_encode([
        'status' => 'success',
        'redirect' => 'appointment.php'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'intentos_restantes' => $max_intentos - ($_SESSION['intentos_fallidos'] ?? $max_intentos)
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    $conexion->close();
}
?>