<?php

require_once 'conexion.php';

//verificar el envio de los datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // capturar y limpiar datos
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $correo = trim($_POST['correo']);
    $password = trim($_POST['password']);
    $edad = isset($_POST['edad']) ? trim($_POST['edad']) : null;
    $tipo_sangre = isset($_POST['tipo_sangre']) ? trim($_POST['tipo_sangre']) : null;
    $peso = isset($_POST['peso']) ? trim($_POST['peso']) : null;
    $alergias = isset($_POST['alergias']) ? trim($_POST['alergias']) : null;
    $enfermedades = isset($_POST['enfermedades_cronicas']) ? trim($_POST['enfermedades_cronicas']) : null;
    
    // validar los campos obligatorios 
    if (empty($nombre) || empty($apellidos) || empty($correo) || empty($password)) {
    die("Todos los campos obligatorio deben ser llenados.");
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die("Correo no válido.");
    }
    
    //proteger contraseña
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    //consulta preparada de SQL    
    $sql = "INSERT INTO usuario (nombre, apellidos, correo, password, edad, tipo_sangre, peso, alergias, enfermedades_cronicas) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssssissss", $nombre, $apellidos, $correo, $password_hash, $edad, $tipo_sangre, 
    $peso, $alergias, $enfermedades);
    
    //ejecutar el registro
    if ($stmt->execute()) {
        echo "exito";
    } else {
        echo "error";
    }
    $stmt->close();
    $conexion->close();
}

?>
