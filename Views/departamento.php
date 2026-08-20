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
    <!-- Asegúrate de tener este archivo CSS o reemplázalo con tus estilos -->
    <link rel="stylesheet" href="assets/estilos.css"> 
    <style>
        /* Un poco de estilo extra para que la gráfica no se desborde */
        .canvas-wrap { position: relative; height: 400px; width: 100%; max-width: 800px; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        .texto-izquierda { text-align: left; }
        .indicadores { display: flex; gap: 20px; margin-bottom: 20px; }
        .indicador { background: #f4f7f6; padding: 15px; border-radius: 8px; flex: 1; text-align: center; }
        .indicador strong { display: block; font-size: 1.5em; color: #2563eb; }
    </style>
</head>
<body>
<header>
    <h1>3. Número de empleados por departamento</h1>
    <p><a href="index.php">⬅ Volver al menú principal</a></p>
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
            indexAxis: 'y', // Barras horizontales
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
</script>
</body>
</html>