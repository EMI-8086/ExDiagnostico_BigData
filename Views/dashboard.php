<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard de RRHH</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; padding: 20px; }
        .chart-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin-bottom: 30px; }
        h2 { text-align: center; color: #333; }
    </style>
</head>
<body>

    <div class="chart-container">
        <h2>1. Evolución de Contrataciones por Año y Género</h2>
        <canvas id="chartContrataciones"></canvas>
    </div>

    <div class="chart-container">
        <h2>2. Salario Promedio por Departamento</h2>
        <canvas id="chartSalarios"></canvas>
    </div>

    <script>
        // Recibimos los datos del controlador procesados y los pasamos a JS
        const datosContrataciones = <?php echo json_encode($datosContrataciones); ?>;
        const datosSalarios = <?php echo json_encode($datosSalarios); ?>;

        // Gráfico 1: Contrataciones
        const ctx1 = document.getElementById('chartContrataciones').getContext('2d');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: datosContrataciones.anios,
                datasets: [
                    {
                        label: 'Hombres (M)',
                        data: datosContrataciones.hombres,
                        borderColor: '#36A2EB',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Mujeres (F)',
                        data: datosContrataciones.mujeres,
                        borderColor: '#FF6384',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: { responsive: true }
        });

        // Gráfico 2: Salarios
        const ctx2 = document.getElementById('chartSalarios').getContext('2d');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: datosSalarios.departamentos,
                datasets: [{
                    label: 'Salario Promedio ($)',
                    data: datosSalarios.salarios,
                    backgroundColor: '#4BC0C0'
                }]
            },
            options: { indexAxis: 'y', responsive: true }
        });
    </script>
</body>
</html>