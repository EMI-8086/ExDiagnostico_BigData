<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Salario Promedio</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; padding: 20px; }
        .chart-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto; }
        .btn-regresar { display: inline-block; margin-bottom: 20px; padding: 10px 15px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 5px; }
        .btn-regresar:hover { background-color: #5a6268; }
        h2 { text-align: center; color: #333; }
    </style>
</head>
<body>

    <a href="index.php" class="btn-regresar">⬅ Regresar al Menú Principal</a>

    <div class="chart-container">
        <h2>Salario Promedio por Departamento</h2>
        <canvas id="chartSalarios"></canvas>
    </div>

    <script>
        const datosSalarios = <?php echo json_encode($datosSalarios); ?>;
        const ctx = document.getElementById('chartSalarios').getContext('2d');
        
        new Chart(ctx, {
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