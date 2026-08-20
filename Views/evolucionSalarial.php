<?php
function escaparEvo($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte 6 - Evolución salarial</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="assets/estilos.css">
</head>
<body>
<header>
    <h1>6. Evolución anual del salario promedio</h1>
    <p><a href="index.php">Volver al menú principal</a></p>
</header>

<main>
    <section class="seccion contenedor" id="reporte-6">
        <p class="descripcion">
            Promedios de los salarios que comenzaron vigencia en cada año, con comparación por género.
        </p>

        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>Año</th>
                        <th>Promedio general</th>
                        <th>Promedio mujeres</th>
                        <th>Promedio hombres</th>
                        <th>Brecha</th>
                        <th>Registros salariales</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datosEvolucion as $registro): ?>
                        <tr>
                            <td><?= escaparEvo($registro['anio']) ?></td>
                            <td>$<?= number_format((float) $registro['promedio_general'], 2) ?></td>
                            <td>$<?= number_format((float) $registro['promedio_mujeres'], 2) ?></td>
                            <td>$<?= number_format((float) $registro['promedio_hombres'], 2) ?></td>
                            <td><?= number_format((float) $registro['brecha_porcentual'], 2) ?>%</td>
                            <td><?= number_format((int) $registro['registros_salariales']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="seccion contenedor" id="grafica-6">
        <h2 class="titulo-seccion">Gráfica 6. Evolución anual del salario promedio</h2>
        <div class="canvas-wrap">
            <canvas id="chartEvolucionSalarial"></canvas>
        </div>

        <h3>Brecha salarial porcentual</h3>
        <div class="canvas-wrap compacto">
            <canvas id="chartBrechaSalarial"></canvas>
        </div>
    </section>
</main>

<script>
    const datosEvolucion = <?= json_encode($datosEvolucion, JSON_UNESCAPED_UNICODE) ?>;
    const aniosEvolucion = datosEvolucion.map(item => item.anio);

    new Chart(document.getElementById('chartEvolucionSalarial'), {
        type: 'line',
        data: {
            labels: aniosEvolucion,
            datasets: [
                { label: 'Promedio general', data: datosEvolucion.map(i => Number(i.promedio_general)), borderColor: '#2563eb', tension: 0.25 },
                { label: 'Mujeres', data: datosEvolucion.map(i => Number(i.promedio_mujeres)), borderColor: '#c026d3', tension: 0.25 },
                { label: 'Hombres', data: datosEvolucion.map(i => Number(i.promedio_hombres)), borderColor: '#059669', tension: 0.25 }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    new Chart(document.getElementById('chartBrechaSalarial'), {
        type: 'line',
        data: {
            labels: aniosEvolucion,
            datasets: [{
                label: 'Brecha porcentual',
                data: datosEvolucion.map(i => Number(i.brecha_porcentual)),
                borderColor: '#ea580c',
                backgroundColor: 'rgba(234, 88, 12, 0.12)',
                fill: true,
                tension: 0.25
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { ticks: { callback: v => `${v}%` } } }
        }
    });
</script>
</body>
</html>
