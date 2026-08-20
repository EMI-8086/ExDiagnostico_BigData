<?php
function escaparInc($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

$incrementoMaximo = 1.0;
foreach ($datosIncremento as $empleado) {
    $incrementoMaximo = max($incrementoMaximo, (float) $empleado['porcentaje_incremento']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte 5 - Incremento salarial</title>
    <link rel="stylesheet" href="assets/estilos.css">
</head>
<body>
<header>
    <h1>5. Empleados con mayor incremento salarial</h1>
    <p><a href="index.php">Volver al menú principal</a></p>
</header>

<main>
    <section class="seccion contenedor" id="reporte-5">
        <p class="descripcion">Comparación del salario mínimo y máximo registrado durante la carrera.</p>

        <form class="filtro" method="get" action="index.php#reporte-5">
            <input type="hidden" name="vista" value="incrementoSalarial">
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
                            <td><?= escaparInc($empleado['emp_no']) ?></td>
                            <td class="texto-izquierda"><?= escaparInc($empleado['empleado']) ?></td>
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
    </section>

    <section class="seccion contenedor" id="grafica-5">
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
                        <?= escaparInc($empleado['empleado']) ?>
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
    </section>
</main>
</body>
</html>
