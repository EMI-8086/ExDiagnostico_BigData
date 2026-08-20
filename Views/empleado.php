<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Consulta de empleado</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f5f5f5;
        }

        .contenedor {
            max-width: 1200px;
            margin: auto;
        }

        .busqueda,
        .seccion {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        input {
            padding: 10px;
            width: 300px;
        }

        button {
            padding: 10px 20px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #eee;
        }

        .error {
            color: #b00020;
            font-weight: bold;
        }

    </style>

</head>

<body>

<div class="contenedor">

    <h1>Consulta de empleado</h1>

    <div class="busqueda">

        <form method="GET">

            <input
                type="hidden"
                name="modulo"
                value="empleado"
            >

            <input
                type="text"
                name="buscar"
                placeholder="Número o nombre del empleado"
                value="<?= htmlspecialchars($busqueda ?? '') ?>"
                required
            >

            <button type="submit">
                Buscar
            </button>

        </form>

    </div>


    <?php if (!empty($resultadosBusqueda)): ?>

        <div class="seccion">

            <h2>Resultados de búsqueda</h2>

            <table>

                <thead>

                    <tr>
                        <th>No. empleado</th>
                        <th>Nombre</th>
                        <th>Género</th>
                        <th>Fecha contratación</th>
                        <th>Acción</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach ($resultadosBusqueda as $resultado): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($resultado['emp_no']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $resultado['first_name'] . ' ' .
                                $resultado['last_name']
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($resultado['gender']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($resultado['hire_date']) ?>
                        </td>

                        <td>

                            <a href="?modulo=empleado&emp_no=<?= urlencode($resultado['emp_no']) ?>">
                                Ver información
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php elseif ($busqueda !== ''): ?>

        <div class="seccion">

            <p class="error">
                No se encontraron empleados.
            </p>

        </div>

    <?php endif; ?>


    <?php if ($empleado): ?>

        <div class="seccion">

            <h2>
                Datos generales
            </h2>

            <p>
                <strong>No. empleado:</strong>
                <?= htmlspecialchars($empleado['emp_no']) ?>
            </p>

            <p>
                <strong>Nombre:</strong>
                <?= htmlspecialchars(
                    $empleado['first_name'] . ' ' .
                    $empleado['last_name']
                ) ?>
            </p>

            <p>
                <strong>Género:</strong>
                <?= htmlspecialchars($empleado['gender']) ?>
            </p>

            <p>
                <strong>Fecha de nacimiento:</strong>
                <?= htmlspecialchars($empleado['birth_date']) ?>
            </p>

            <p>
                <strong>Fecha de contratación:</strong>
                <?= htmlspecialchars($empleado['hire_date']) ?>
            </p>

        </div>


        <div class="seccion">

            <h2>
                Historial salarial
            </h2>

            <table>

                <thead>

                    <tr>
                        <th>Salario</th>
                        <th>Desde</th>
                        <th>Hasta</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach ($salarios as $salario): ?>

                    <tr>

                        <td>
                            $<?= number_format(
                                $salario['salary'],
                                2
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($salario['from_date']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($salario['to_date']) ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>


        <div class="seccion">

            <h2>
                Departamentos
            </h2>

            <table>

                <thead>

                    <tr>
                        <th>Clave</th>
                        <th>Departamento</th>
                        <th>Desde</th>
                        <th>Hasta</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach ($departamentos as $departamento): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($departamento['dept_no']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($departamento['dept_name']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($departamento['from_date']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($departamento['to_date']) ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>


        <div class="seccion">

            <h2>
                Historial de puestos
            </h2>

            <table>

                <thead>

                    <tr>
                        <th>Puesto</th>
                        <th>Desde</th>
                        <th>Hasta</th>
                    </tr>

                </thead>

                <tbody>

                <?php foreach ($puestos as $puesto): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars($puesto['title']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($puesto['from_date']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($puesto['to_date']) ?>
                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

</div>

</body>

</html>