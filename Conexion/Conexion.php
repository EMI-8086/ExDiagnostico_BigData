<?php
// Configuración de la conexión
$host = "localhost";     // Servidor de base de datos
$usuario = "root";       // Usuario de MySQL
$clave = "";             // Contraseña de MySQL
$baseDatos = "employeesdb";  // Nombre de la base de datos

// Crear conexión usando MySQLi orientado a objetos
$conexion = new mysqli($host, $usuario, $clave, $baseDatos);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Establecer el conjunto de caracteres a UTF-8
if (!$conexion->set_charset("utf8mb4")) {
    die("Error configurando charset: " . $conexion->error);
}

// Cerrar conexión
$conexion->close();
?>
