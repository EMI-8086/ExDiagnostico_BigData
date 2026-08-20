<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Evolución de Contrataciones</title>
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
        <h2>Evolución de Contrataciones por Año y Género</h2>
        <canvas id="chartContrataciones"></canvas>
    </div>

    <script>
        const datosContrataciones = <?php echo json_encode($datosContrataciones); ?>;
        const ctx = document.getElementById('chartContrataciones').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: datosContrataciones.anios,
                datasets: [
                    {
                        label: 'Hombres (M)',
                        data: datosContrataciones.hombres,
                        borderColor: '#36A2EB',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        tension: 0.3, fill: true
                    },
                    {
                        label: 'Mujeres (F)',
                        data: datosContrataciones.mujeres,
                        borderColor: '#FF6384',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.3, fill: true
                    }
                ]
            },
            options: { responsive: true }
        });
    </script>
</body>
</html>