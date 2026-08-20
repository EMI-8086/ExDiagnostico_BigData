<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte: Salario Promedio</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; padding: 20px; }
        .report-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto; }
        .btn-regresar { display: inline-block; margin-bottom: 20px; padding: 10px 15px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 5px; }
        .btn-regresar:hover { background-color: #5a6268; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #28a745; color: white; }
        tr:hover { background-color: #f1f1f1; }
    </style>
</head>
<body>

    <a href="index.php" class="btn-regresar">⬅ Regresar al Menú Principal</a>

    <div class="report-container">
        <h2>Reporte: Salario Promedio por Departamento</h2>
        <p><strong>Objetivo:</strong> Identificar departamentos con salarios más altos para decisiones de presupuesto.</p>
        
        <table>
            <thead>
                <tr>
                    <th>Departamento</th>
                    <th>Salario Promedio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($datosReporte as $fila): ?>
                <tr>
                    <td><?php echo htmlspecialchars($fila['departamento']); ?></td>
                    <td>$<?php echo number_format($fila['salario_promedio'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>