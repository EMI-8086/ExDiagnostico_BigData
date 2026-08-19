<?php

function escapar($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$totalDepartamentos = count($datosDepartamentos);
$totalEmpleadosDepartamento = 0;

foreach ($datosDepartamentos as $departamento) {
    $totalEmpleadosDepartamento += (int) $departamento['total_empleados'];
}

$totalMujeres = 0;
$totalHombres = 0;

foreach ($datosEdades as $generos) {
    $totalMujeres += (int) $generos['Mujeres'];
    $totalHombres += (int) $generos['Hombres'];
}

$incrementoMaximo = 1.0;

foreach ($datosIncremento as $empleado) {
    $incrementoMaximo = max(
        $incrementoMaximo,
        (float) $empleado['porcentaje_incremento']
    );
}

$opcionesJson = JSON_UNESCAPED_UNICODE
    | JSON_HEX_TAG
    | JSON_HEX_AMP
    | JSON_HEX_APOS
    | JSON_HEX_QUOT;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Recursos Humanos</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            font-family: Arial, Helvetica, sans-serif;
            color: #172033;
            background: #f4f6f9;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background: #f4f6f9;
        }

        header {
            padding: 32px 20px;
            background: #17375e;
            color: #fff;
            text-align: center;
        }

        header h1 {
            margin: 0 0 8px;
        }

        header p {
            margin: 0;
            color: #dbeafe;
        }

        main {
            width: min(1220px, calc(100% - 32px));
            margin: 28px auto 60px;
        }

        .menus {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 30px;
        }

        .menu,
        .contenedor {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 6px 18px rgb(15 23 42 / 7%);
        }

        .menu {
            padding: 20px;
        }

        .menu h2 {
            margin: 0 0 14px;
            font-size: 20px;
        }

        .menu-enlaces {
            display: grid;
            gap: 9px;
        }

        .menu a {
            padding: 10px 12px;
            border-radius: 7px;
            background: #eff6ff;
            color: #1d4ed8;
            text-decoration: none;
        }

        .menu a:hover {
            background: #dbeafe;
        }

        .seccion {
            scroll-margin-top: 18px;
            margin-bottom: 30px;
        }

        .contenedor {
            padding: 22px;
        }

        h2,
        h3 {
            color: #172033;
        }

        .titulo-seccion {
            margin: 0 0 6px;
        }

        .descripcion {
            margin: 0 0 20px;
            color: #526176;
        }

        .indicadores {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin: 18px 0;
        }

        .indicador {
            padding: 16px;
            border: 1px solid #dbe3ee;
            border-radius: 9px;
            background: #f8fafc;
        }

        .indicador span {
            display: block;
            margin-bottom: 5px;
            color: #64748b;
            font-size: 13px;
        }

        .indicador strong {
            color: #17375e;
            font-size: 22px;
        }

        .filtro {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            gap: 12px;
            margin: 18px 0;
        }

        .filtro label {
            display: grid;
            gap: 6px;
            font-weight: 700;
        }

        input,
        select,
        button {
            min-height: 40px;
            border-radius: 6px;
            font: inherit;
        }

        input,
        select {
            padding: 0 12px;
            border: 1px solid #cbd5e1;
            background: #fff;
        }

        button {
            border: 0;
            padding: 0 18px;
            background: #2563eb;
            color: #fff;
            cursor: pointer;
            font-weight: 700;
        }

        .tabla-contenedor {
            overflow-x: auto;
            border: 1px solid #e2e8f0;
            border-radius: 9px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
            text-align: right;
            white-space: nowrap;
        }

        th {
            background: #1e3a5f;
            color: #fff;
        }

        th:first-child,
        td:first-child,
        .texto-izquierda {
            text-align: left;
        }

        tbody tr:hover {
            background: #eff6ff;
        }

        .mujeres {
            color: #a21caf;
            font-weight: 700;
        }

        .hombres,
        .incremento {
            color: #047857;
            font-weight: 700;
        }

        .canvas-wrap {
            position: relative;
            min-height: 390px;
        }

        .canvas-wrap.compacto {
            min-height: 320px;
        }

        .grafica-progreso {
            display: grid;
            gap: 12px;
        }

        .fila-progreso {
            display: grid;
            grid-template-columns: minmax(180px, 260px) 1fr 82px;
            align-items: center;
            gap: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .nombre-empleado {
            overflow: hidden;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .nombre-empleado small {
            display: block;
            margin-top: 3px;
            color: #64748b;
            font-weight: 400;
        }

        .pista {
            height: 28px;
            overflow: hidden;
            border-radius: 6px;
            background: #e8edf5;
        }

        .barra {
            height: 100%;
            border-radius: 6px;
            background: linear-gradient(90deg, #2563eb, #059669);
        }

        .valor-progreso {
            color: #047857;
            font-weight: 800;
            text-align: right;
        }

        .volver {
            display: inline-block;
            margin-top: 16px;
            color: #2563eb;
            text-decoration: none;
        }

        @media (max-width: 760px) {
            .menus,
            .indicadores {
                grid-template-columns: 1fr;
            }

            .fila-progreso {
                grid-template-columns: 1fr;
            }

            .valor-progreso {
                text-align: left;
            }
        }
    </style>
</head>
<body>
<header id="inicio">
    <h1>Dashboard de Recursos Humanos</h1>
    <p>Reportes y gráficas estadísticas de la base de datos Employees</p>
</header>

<main>
    <section class="menus" aria-label="Menús principales">
        <nav class="menu">
            <h2>Menú de reportes</h2>
            <div class="menu-enlaces">
                <a href="#reporte-1">1. Contrataciones por año y género</a>
                <a href="#reporte-2">2. Salario promedio por departamento</a>
                <a href="#reporte-3">3. Empleados por departamento</a>
                <a href="#reporte-4">4. Rangos de edad y género</a>
                <a href="#reporte-5">5. Mayor incremento salarial</a>
                <a href="#reporte-6">6. Evolución salarial anual</a>
            </div>
        </nav>

        <nav class="menu">
            <h2>Menú de gráficas</h2>
            <div class="menu-enlaces">
                <a href="#grafica-1">1. Contrataciones por año y género</a>
                <a href="#grafica-2">2. Salario promedio por departamento</a>
                <a href="#grafica-3">3. Empleados por departamento</a>
                <a href="#grafica-4">4. Pirámide de edad y género</a>
                <a href="#grafica-5">5. Incremento salarial</a>
                <a href="#grafica-6">6. Evolución salarial</a>
            </div>
        </nav>
    </section>

    <!-- Reportes que corresponden a las consultas 1 y 2 -->
    <section id="reporte-1" class="seccion contenedor">
        <h2 class="titulo-seccion">1. Evolución de contrataciones por año y género</h2>
        <p class="descripcion">Cantidad anual de contrataciones de mujeres, hombres y total general.</p>

        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>Año</th>
                        <th>Mujeres</th>
                        <th>Hombres</th>
                        <th>Total de contrataciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datosContrataciones['anios'] as $indice => $anio): ?>
                        <?php
                            $mujeres = (int) $datosContrataciones['mujeres'][$indice];
                            $hombres = (int) $datosContrataciones['hombres'][$indice];
                        ?>
                        <tr>
                            <td><?= escapar($anio) ?></td>
                            <td class="mujeres"><?= number_format($mujeres) ?></td>
                            <td class="hombres"><?= number_format($hombres) ?></td>
                            <td><?= number_format($mujeres + $hombres) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a class="volver" href="#inicio">Volver al menú</a>
    </section>

    <section id="reporte-2" class="seccion contenedor">
        <h2 class="titulo-seccion">2. Salario promedio por departamento</h2>
        <p class="descripcion">Departamentos ordenados de mayor a menor salario promedio.</p>

        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>Departamento</th>
                        <th>Salario promedio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datosSalarios['departamentos'] as $indice => $departamento): ?>
                        <tr>
                            <td><?= escapar($departamento) ?></td>
                            <td>$<?= number_format((float) $datosSalarios['salarios'][$indice], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a class="volver" href="#inicio">Volver al menú</a>
    </section>

    <!-- Gráficas que realizó la Persona 1 -->
    <section id="grafica-1" class="seccion contenedor">
        <h2 class="titulo-seccion">1. Evolución de contrataciones por año y género</h2>
        <p class="descripcion">Comparación anual de contrataciones de mujeres y hombres.</p>
        <div class="canvas-wrap">
            <canvas id="chartContrataciones"></canvas>
        </div>
        <a class="volver" href="#inicio">Volver al menú</a>
    </section>

    <section id="grafica-2" class="seccion contenedor">
        <h2 class="titulo-seccion">2. Salario promedio por departamento</h2>
        <p class="descripcion">Comparación del salario promedio entre departamentos.</p>
        <div class="canvas-wrap">
            <canvas id="chartSalarios"></canvas>
        </div>
        <a class="volver" href="#inicio">Volver al menú</a>
    </section>

    <!-- Reporte y gráfica 3 -->
    <section id="reporte-3" class="seccion contenedor">
        <h2 class="titulo-seccion">3. Número de empleados por departamento</h2>
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
                <strong><?= escapar($datosDepartamentos[0]['departamento'] ?? 'Sin datos') ?></strong>
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
                            <td><?= escapar($departamento['dept_no']) ?></td>
                            <td class="texto-izquierda"><?= escapar($departamento['departamento']) ?></td>
                            <td><?= number_format((int) $departamento['total_empleados']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a class="volver" href="#inicio">Volver al menú</a>
    </section>

    <section id="grafica-3" class="seccion contenedor">
        <h2 class="titulo-seccion">Gráfica 3. Empleados por departamento</h2>
        <p class="descripcion">Gráfica de barras con el tamaño actual de cada departamento.</p>
        <div class="canvas-wrap">
            <canvas id="chartEmpleadosDepartamento"></canvas>
        </div>
        <a class="volver" href="#inicio">Volver al menú</a>
    </section>

    <!-- Reporte y gráfica 4 -->
    <section id="reporte-4" class="seccion contenedor">
        <h2 class="titulo-seccion">4. Empleados por rangos de edad y género</h2>
        <p class="descripcion">La edad y la condición laboral se calculan con la fecha seleccionada.</p>

        <form class="filtro" method="get" action="index.php#reporte-4">
            <input type="hidden" name="top" value="<?= $top ?>">
            <label for="fecha">
                Fecha de referencia
                <input
                    id="fecha"
                    name="fecha"
                    type="date"
                    min="1985-01-01"
                    max="<?= date('Y-m-d') ?>"
                    value="<?= escapar($fechaReferencia) ?>"
                    required
                >
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
                            <td><?= escapar($rango) ?></td>
                            <td class="mujeres"><?= number_format((int) $generos['Mujeres']) ?></td>
                            <td class="hombres"><?= number_format((int) $generos['Hombres']) ?></td>
                            <td><?= number_format((int) $generos['Mujeres'] + (int) $generos['Hombres']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a class="volver" href="#inicio">Volver al menú</a>
    </section>

    <section id="grafica-4" class="seccion contenedor">
        <h2 class="titulo-seccion">Gráfica 4. Pirámide de población por edad y género</h2>
        <p class="descripcion">Los valores de mujeres se muestran a la izquierda y los de hombres a la derecha.</p>
        <div class="canvas-wrap">
            <canvas id="chartEdadesGenero"></canvas>
        </div>
        <a class="volver" href="#inicio">Volver al menú</a>
    </section>

    <!-- Reporte y gráfica 5 -->
    <section id="reporte-5" class="seccion contenedor">
        <h2 class="titulo-seccion">5. Empleados con mayor incremento salarial</h2>
        <p class="descripcion">Comparación del salario mínimo y máximo registrado durante la carrera.</p>

        <form class="filtro" method="get" action="index.php#reporte-5">
            <input type="hidden" name="fecha" value="<?= escapar($fechaReferencia) ?>">
            <label for="top">
                Cantidad de empleados
                <select id="top" name="top">
                    <?php foreach ($opcionesTop as $opcion): ?>
                        <option value="<?= $opcion ?>" <?= $top === $opcion ? 'selected' : '' ?>>
                            Top <?= $opcion ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <button type="submit">Actualizar</button>
        </form>

        <div class="tabla-contenedor">
            <table>
                <thead>
                    <tr>
                        <th>N.º empleado</th>
                        <th>Empleado</th>
                        <th>Salario mínimo</th>
                        <th>Salario máximo</th>
                        <th>Incremento</th>
                        <th>Años de carrera</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datosIncremento as $empleado): ?>
                        <tr>
                            <td><?= escapar($empleado['emp_no']) ?></td>
                            <td class="texto-izquierda"><?= escapar($empleado['empleado']) ?></td>
                            <td>$<?= number_format((int) $empleado['salario_minimo']) ?></td>
                            <td>$<?= number_format((int) $empleado['salario_maximo']) ?></td>
                            <td class="incremento">
                                <?= number_format((float) $empleado['porcentaje_incremento'], 2) ?>%
                            </td>
                            <td><?= number_format((int) $empleado['anios_carrera']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a class="volver" href="#inicio">Volver al menú</a>
    </section>

    <section id="grafica-5" class="seccion contenedor">
        <h2 class="titulo-seccion">Gráfica 5. Mayor incremento salarial</h2>
        <p class="descripcion">Tabla con barras de progreso, alternativa permitida por el examen.</p>

        <div class="grafica-progreso">
            <?php foreach ($datosIncremento as $empleado): ?>
                <?php
                    $porcentaje = (float) $empleado['porcentaje_incremento'];
                    $ancho = min(100, ($porcentaje / $incrementoMaximo) * 100);
                ?>
                <div class="fila-progreso">
                    <div class="nombre-empleado">
                        <?= escapar($empleado['empleado']) ?>
                        <small>
                            $<?= number_format((int) $empleado['salario_minimo']) ?> a
                            $<?= number_format((int) $empleado['salario_maximo']) ?>
                        </small>
                    </div>
                    <div class="pista">
                        <div class="barra" style="width: <?= number_format($ancho, 2, '.', '') ?>%"></div>
                    </div>
                    <div class="valor-progreso"><?= number_format($porcentaje, 2) ?>%</div>
                </div>
            <?php endforeach; ?>
        </div>
        <a class="volver" href="#inicio">Volver al menú</a>
    </section>

    <!-- Reporte y gráfica 6 -->
    <section id="reporte-6" class="seccion contenedor">
        <h2 class="titulo-seccion">6. Evolución anual del salario promedio</h2>
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
                            <td><?= escapar($registro['anio']) ?></td>
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
        <a class="volver" href="#inicio">Volver al menú</a>
    </section>

    <section id="grafica-6" class="seccion contenedor">
        <h2 class="titulo-seccion">Gráfica 6. Evolución anual del salario promedio</h2>
        <p class="descripcion">Tendencia general y comparación del promedio salarial por género.</p>
        <div class="canvas-wrap">
            <canvas id="chartEvolucionSalarial"></canvas>
        </div>

        <h3>Brecha salarial porcentual</h3>
        <div class="canvas-wrap compacto">
            <canvas id="chartBrechaSalarial"></canvas>
        </div>
        <a class="volver" href="#inicio">Volver al menú</a>
    </section>
</main>

<script>
    const datosContrataciones = <?= json_encode($datosContrataciones, $opcionesJson) ?>;
    const datosSalarios = <?= json_encode($datosSalarios, $opcionesJson) ?>;
    const datosDepartamentos = <?= json_encode($datosDepartamentos, $opcionesJson) ?>;
    const datosEdades = <?= json_encode($datosEdades, $opcionesJson) ?>;
    const datosEvolucion = <?= json_encode($datosEvolucion, $opcionesJson) ?>;

    new Chart(document.getElementById('chartContrataciones'), {
        type: 'line',
        data: {
            labels: datosContrataciones.anios,
            datasets: [
                {
                    label: 'Hombres (M)',
                    data: datosContrataciones.hombres,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.15)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Mujeres (F)',
                    data: datosContrataciones.mujeres,
                    borderColor: '#db2777',
                    backgroundColor: 'rgba(219, 39, 119, 0.15)',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    new Chart(document.getElementById('chartSalarios'), {
        type: 'bar',
        data: {
            labels: datosSalarios.departamentos,
            datasets: [{
                label: 'Salario promedio ($)',
                data: datosSalarios.salarios,
                backgroundColor: '#0f9f8f'
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false
        }
    });

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
            plugins: {
                legend: { display: false }
            }
        }
    });

    const rangosEdad = Object.keys(datosEdades);
    const mujeresEdad = rangosEdad.map(rango => -Number(datosEdades[rango].Mujeres));
    const hombresEdad = rangosEdad.map(rango => Number(datosEdades[rango].Hombres));

    new Chart(document.getElementById('chartEdadesGenero'), {
        type: 'bar',
        data: {
            labels: rangosEdad,
            datasets: [
                {
                    label: 'Mujeres',
                    data: mujeresEdad,
                    backgroundColor: '#c026d3'
                },
                {
                    label: 'Hombres',
                    data: hombresEdad,
                    backgroundColor: '#059669'
                }
            ]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                    ticks: {
                        callback: valor => Math.abs(valor).toLocaleString('es-MX')
                    }
                },
                y: { stacked: true }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: contexto =>
                            `${contexto.dataset.label}: ${Math.abs(contexto.raw).toLocaleString('es-MX')}`
                    }
                }
            }
        }
    });

    const aniosEvolucion = datosEvolucion.map(item => item.anio);

    new Chart(document.getElementById('chartEvolucionSalarial'), {
        type: 'line',
        data: {
            labels: aniosEvolucion,
            datasets: [
                {
                    label: 'Promedio general',
                    data: datosEvolucion.map(item => Number(item.promedio_general)),
                    borderColor: '#2563eb',
                    tension: 0.25
                },
                {
                    label: 'Mujeres',
                    data: datosEvolucion.map(item => Number(item.promedio_mujeres)),
                    borderColor: '#c026d3',
                    tension: 0.25
                },
                {
                    label: 'Hombres',
                    data: datosEvolucion.map(item => Number(item.promedio_hombres)),
                    borderColor: '#059669',
                    tension: 0.25
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    new Chart(document.getElementById('chartBrechaSalarial'), {
        type: 'line',
        data: {
            labels: aniosEvolucion,
            datasets: [{
                label: 'Brecha porcentual',
                data: datosEvolucion.map(item => Number(item.brecha_porcentual)),
                borderColor: '#ea580c',
                backgroundColor: 'rgba(234, 88, 12, 0.12)',
                fill: true,
                tension: 0.25
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    ticks: {
                        callback: valor => `${valor}%`
                    }
                }
            }
        }
    });
</script>
</body>
</html>
