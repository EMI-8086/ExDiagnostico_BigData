<?php

// 1. Requerir dependencias (config es único y sirve para todos los controladores)
require_once __DIR__ . '/config/Conexion.php';

// 2. Inicializar conexión (compartida por todos los controladores)
$con = new Conexion();
$db = $con->conectar();

// 3. Enrutamiento: cada vista se conecta únicamente con su propio controlador
$vista = $_GET['vista'] ?? 'menu';

// Filtros comunes (reportes 4 y 5)
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

switch ($vista) {

    // --- Persona 1 (Emil): Reportes 1 y 2 ---
    case 'contrataciones':
    case 'salarios':
        require_once __DIR__ . '/Controller/DashboardController.php';
        $controller = new DashboardController($db);
        $datosContrataciones = $controller->getDatosContrataciones();
        $datosSalarios = $controller->getDatosSalarios();
        require_once __DIR__ . '/Views/dashboard.php'; // vista de Emil (reportes 1-2)
        break;

    // Reporte 3 ---
    case 'departamento':
        require_once __DIR__ . '/Controller/DepartamentoController.php';
        $controller = new DepartamentoController($db);
        $datosDepartamentos = $controller->getEmpleadosDepartamento();
        require_once __DIR__ . '/Views/departamento.php';
        break;

    //  Reporte 4 ---
    case 'edadGenero':
        require_once __DIR__ . '/Controller/EdadGeneroController.php';
        $controller = new EdadGeneroController($db);
        $datosEdades = $controller->getEdadesGenero($fechaReferencia);
        require_once __DIR__ . '/Views/edadGenero.php';
        break;

    // Reporte 5 ---
    case 'incrementoSalarial':
        require_once __DIR__ . '/Controller/IncrementoSalarialController.php';
        $controller = new IncrementoSalarialController($db);
        $datosIncremento = $controller->getIncrementoSalarial($top);
        require_once __DIR__ . '/Views/incrementoSalarial.php';
        break;

    // Reporte 6 ---
    case 'evolucionSalarial':
        require_once __DIR__ . '/Controller/EvolucionSalarialController.php';
        $controller = new EvolucionSalarialController($db);
        $datosEvolucion = $controller->getEvolucionSalarial();
        require_once __DIR__ . '/Views/evolucionSalarial.php';
        break;

    // --- Menú principal ---
    default:
        require_once __DIR__ . '/Views/menu.php';
        break;
}
