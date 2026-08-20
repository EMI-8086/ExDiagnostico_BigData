<?php
function escaparDep($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$totalDepartamentos = count($datosDepartamentos);
$totalEmpleadosDepartamento = 0;
foreach ($datosDepartamentos as $d) {
    $totalEmpleadosDepartamento += (int) $d['total_empleados'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte 3 - Empleados por departamento</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="assets/estilos.css">
</head>
<body>
<header>
    <h1>3. Número de empleados por departamento</h1>
    <p><a href="index.php">Volver al menú principal</a></p>
</header>

<main>
    <section class="seccion contenedor" id="reporte-3">
        <p class="descripcion">Empleados cuya asignación departamental continúa vigente.</p>

        <div class="indicadores">
            <article class="indicador">
                <span>Departamentos</span>
                <strong><?= number_format($totalDepartamentos) ?></strong>
            </article>
            <article class="indicador">
                <span>Total de empleados actuales</span>
                <strong><?= number_format($totalEmpleadosDepartamento) ?></strong>
            </article>
            <article class="indicador">
                <span>Departamento más grande</span>
                <strong><?= escaparDep($datosDepartamentos[0]['departamento'] ?? 'Sin datos') ?></strong>
            </article>
        </div>

        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>Clave</th>
                        <th>Departamento</th>
                        <th>Total de empleados</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datosDepartamentos as $departamento): ?>
                        <tr>
                            <td><?= escaparDep($departamento['dept_no']) ?></td>
                            <td class="texto-izquierda"><?= escaparDep($departamento['departamento']) ?></td>
                            <td><?= number_format((int) $departamento['total_empleados']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="seccion contenedor" id="grafica-3">
        <h2 class="titulo-seccion">Gráfica 3. Empleados por departamento</h2>
        <div class="canvas-wrap">
            <canvas id="chartEmpleadosDepartamento"></canvas>
        </div>
    </section>
</main>

<script>
    const datosDepartamentos = <?= json_encode($datosDepartamentos, JSON_UNESCAPED_UNICODE) ?>;

    new Chart(document.getElementById('chartEmpleadosDepartamento'), {
        type: 'bar',
        data: {
            labels: datosDepartamentos.map(item => item.departamento),
            datasets: [{
                label: 'Empleados actuales',
                data: datosDepartamentos.map(item => Number(item.total_empleados)),
                backgroundColor: '#2563eb'
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
</script>
</body>
</html>
