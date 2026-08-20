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
    // Reporte 1: Evolución de Contrataciones
    case 'contrataciones':
        require_once __DIR__ . '/Controller/ContratacionesController.php';
        $controller = new ContratacinoesController($db);
        $datosContrataciones = $controller->getDatosContrataciones();
        require_once __DIR__ . '/Views/contrataciones.php';
        break;

    // Reporte 2: Salario Promedio por Departamento
    case 'salario':
        require_once __DIR__ . '/Controller/SalarioController.php';
        $controller = new SalarioController($db);
        $datosSalarios = $controller->getDatosSalarios();
        require_once __DIR__ . '/Views/salario.php';
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
