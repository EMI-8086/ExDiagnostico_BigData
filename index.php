<?php
// 1. Requerir dependencias principales
require_once __DIR__ . '/config/Conexion.php';

// 2. Inicializar conexión (compartida por todos los controladores)
$con = new Conexion();
$db = $con->conectar();

// 3. Variables compartidas y filtros (Reportes 4 y 5)
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

// 4. Enrutamiento Unificado
// Captura 'vista' (tu diseño) o 'modulo' (diseño de tu compañero)
$vista = $_GET['vista'] ?? $_GET['modulo'] ?? 'menu';

switch ($vista) {

    case 'empleado':
        require_once __DIR__ . '/Controller/EmpleadoController.php';
        $empleadoController = new EmpleadoController($db);
        
        $busqueda = trim($_GET['buscar'] ?? '');
        $resultadosBusqueda = [];

        if ($busqueda !== '') {
            $resultadosBusqueda = $empleadoController->buscarEmpleados($busqueda);
        }

        $empleado = null;
        $salarios = [];
        $departamentos = [];
        $puestos = [];

        if (isset($_GET['emp_no']) && ctype_digit($_GET['emp_no'])) {
            $empNo = (int) $_GET['emp_no'];
            $empleado = $empleadoController->getEmpleado($empNo);

            if ($empleado) {
                $salarios = $empleadoController->getSalarios($empNo);
                $departamentos = $empleadoController->getDepartamentos($empNo);
                $puestos = $empleadoController->getPuestos($empNo);
            }
        }

        require_once __DIR__ . '/Views/empleado.php';
        break;

    case 'contrataciones':
        require_once __DIR__ . '/Controller/ContratacionesController.php';
        $controller = new ContratacionesController($db);
        
        $datosContrataciones = $controller->getDatosContrataciones();
        require_once __DIR__ . '/Views/contrataciones.php';
        break;

    case 'salario':
        require_once __DIR__ . '/Controller/SalarioController.php';
        $controller = new SalarioController($db);
        $datosSalarios = $controller->getDatosSalarios();
        require_once __DIR__ . '/Views/salario.php';
        break;

    case 'edadGenero':
        require_once __DIR__ . '/Controller/EdadGeneroController.php';
        $controller = new EdadGeneroController($db);
        $datosEdades = $controller->getEdadesGenero($fechaReferencia);
        require_once __DIR__ . '/Views/edadGenero.php';
        break;

    case 'incrementoSalarial':
        require_once __DIR__ . '/Controller/IncrementoSalarialController.php';
        $controller = new IncrementoSalarialController($db);
        $datosIncremento = $controller->getIncrementoSalarial($top);
        require_once __DIR__ . '/Views/incrementoSalarial.php';
        break;

    case 'evolucionSalarial':
        require_once __DIR__ . '/Controller/EvolucionSalarialController.php';
        $controller = new EvolucionSalarialController($db);
        $datosEvolucion = $controller->getEvolucionSalarial();
        require_once __DIR__ . '/Views/evolucionSalarial.php';
        break;

    default:
        require_once __DIR__ . '/Views/menu.php';
        break;
}

?>