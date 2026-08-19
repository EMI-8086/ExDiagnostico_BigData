<?php

// 1. Requerir dependencias
require_once __DIR__ . '/config/Conexion.php';
require_once __DIR__ . '/Controller/DashboardController.php';

// 2. Inicializar conexión y controlador
$con = new Conexion();
$db = $con->conectar();
$controller = new DashboardController($db);

// 3. Validar filtros de los reportes 4 y 5
$fechaPredeterminada = '2002-12-31';
$fechaSolicitada = $_GET['fecha'] ?? $fechaPredeterminada;

if (!is_string($fechaSolicitada)) {
    $fechaSolicitada = $fechaPredeterminada;
}

$fechaValidada = DateTime::createFromFormat('!Y-m-d', $fechaSolicitada);
$fechaReferencia = (
    $fechaValidada !== false
    && $fechaValidada->format('Y-m-d') === $fechaSolicitada
) ? $fechaSolicitada : $fechaPredeterminada;

$opcionesTop = [5, 10, 20, 50];
$topSolicitado = filter_input(INPUT_GET, 'top', FILTER_VALIDATE_INT);
$top = in_array($topSolicitado, $opcionesTop, true) ? $topSolicitado : 10;

// 4. Obtener los datos de las consultas 1 a 6
$datosContrataciones = $controller->getDatosContrataciones();
$datosSalarios = $controller->getDatosSalarios();
$datosDepartamentos = $controller->getEmpleadosDepartamento();
$datosEdades = $controller->getEdadesGenero($fechaReferencia);
$datosIncremento = $controller->getIncrementoSalarial($top);
$datosEvolucion = $controller->getEvolucionSalarial();

// 5. Cargar la vista principal
require_once __DIR__ . '/Views/dashboard.php';
