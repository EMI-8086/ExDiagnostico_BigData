<?php
// 1. Requerir dependencias
require_once 'config/Conexion.php';
require_once 'Controller/DashboardController.php';

// 2. Inicializar conexión y controlador
$con = new Conexion();
$db = $con->conectar();
$controller = new DashboardController($db);

// 3. Obtener los datos usando los métodos del controlador separados
$datosContrataciones = $controller->getDatosContrataciones();
$datosSalarios = $controller->getDatosSalarios();

// 4. Cargar la vista (que ahora tendrá acceso a las variables de arriba)
require_once 'views/dashboard.php';
?>