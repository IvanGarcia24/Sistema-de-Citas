<?php
//datos de conexion
$host = "localhost";
$usuario = "root";
$contraseña = "13Elizabeth*";
$base_datos = "citas";

//establecer conexion
$conexion = new mysqli($host, $usuario, $contraseña, $base_datos);


//en caso de haber un error en la conexion
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

?>


