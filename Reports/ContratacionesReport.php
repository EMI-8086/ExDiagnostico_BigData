<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte: Evolución de Contrataciones</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; padding: 20px; }
        .report-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto; }
        .btn-regresar { display: inline-block; margin-bottom: 20px; padding: 10px 15px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 5px; }
        .btn-regresar:hover { background-color: #5a6268; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #007bff; color: white; }
        tr:hover { background-color: #f1f1f1; }
    </style>
</head>
<body>

    <a href="index.php" class="btn-regresar">⬅ Regresar al Menú Principal</a>

    <div class="report-container">
        <h2>Reporte: Evolución de Contrataciones por Año y Género</h2>
        <p><strong>Objetivo:</strong> Identificar tendencias de contratación y evolución de la diversidad de género.</p>
        
        <table>
            <thead>
                <tr>
                    <th>Año</th>
                    <th>Género</th>
                    <th>Total de Contrataciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($datosReporte as $fila): ?>
                <tr>
                    <td><?php echo $fila['anio']; ?></td>
                    <td><?php echo $fila['genero'] === 'M' ? 'Masculino' : 'Femenino'; ?></td>
                    <td><?php echo number_format($fila['total_contrataciones']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>