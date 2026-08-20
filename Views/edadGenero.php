<?php
function escaparEdad($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$totalMujeres = 0;
$totalHombres = 0;
foreach ($datosEdades as $generos) {
    $totalMujeres += (int) $generos['Mujeres'];
    $totalHombres += (int) $generos['Hombres'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte 4 - Edades y género</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="assets/estilos.css">
</head>
<body>
<header>
    <h1>4. Empleados por rangos de edad y género</h1>
    <p><a href="index.php">Volver al menú principal</a></p>
</header>

<main>
    <section class="seccion contenedor" id="reporte-4">
        <p class="descripcion">La edad y la condición laboral se calculan con la fecha seleccionada.</p>

        <form class="filtro" method="get" action="index.php#reporte-4">
            <input type="hidden" name="vista" value="edadGenero">
            <label for="fecha">
                Fecha de referencia
                <input id="fecha" name="fecha" type="date" min="1985-01-01"
                       max="<?= date('Y-m-d') ?>"
                       value="<?= escaparEdad($fechaReferencia) ?>" required>
            </label>
            <button type="submit">Actualizar</button>
        </form>

        <div class="indicadores">
            <article class="indicador">
                <span>Total de empleados activos</span>
                <strong><?= number_format($totalMujeres + $totalHombres) ?></strong>
            </article>
            <article class="indicador">
                <span>Mujeres</span>
                <strong><?= number_format($totalMujeres) ?></strong>
            </article>
            <article class="indicador">
                <span>Hombres</span>
                <strong><?= number_format($totalHombres) ?></strong>
            </article>
        </div>

        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>Rango de edad</th>
                        <th>Mujeres</th>
                        <th>Hombres</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datosEdades as $rango => $generos): ?>
                        <tr>
                            <td><?= escaparEdad($rango) ?></td>
                            <td class="mujeres"><?= number_format((int) $generos['Mujeres']) ?></td>
                            <td class="hombres"><?= number_format((int) $generos['Hombres']) ?></td>
                            <td><?= number_format((int) $generos['Mujeres'] + (int) $generos['Hombres']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="seccion contenedor" id="grafica-4">
        <h2 class="titulo-seccion">Gráfica 4. Pirámide de población por edad y género</h2>
        <div class="canvas-wrap">
            <canvas id="chartEdadesGenero"></canvas>
        </div>
    </section>
</main>

<script>
    const datosEdades = <?= json_encode($datosEdades, JSON_UNESCAPED_UNICODE) ?>;

    const rangosEdad = Object.keys(datosEdades);
    const mujeresEdad = rangosEdad.map(r => -Number(datosEdades[r].Mujeres));
    const hombresEdad = rangosEdad.map(r => Number(datosEdades[r].Hombres));

    new Chart(document.getElementById('chartEdadesGenero'), {
        type: 'bar',
        data: {
            labels: rangosEdad,
            datasets: [
                { label: 'Mujeres', data: mujeresEdad, backgroundColor: '#c026d3' },
                { label: 'Hombres', data: hombresEdad, backgroundColor: '#059669' }
            ]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                    ticks: { callback: v => Math.abs(v).toLocaleString('es-MX') }
                },
                y: { stacked: true }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: c => `${c.dataset.label}: ${Math.abs(c.raw).toLocaleString('es-MX')}`
                    }
                }
            }
        }
    });
</script>
</body>
</html>
