<?php
// Reporte 4: Empleados por rangos de edad y género
class EdadGeneroController {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function getEdadesGenero($fechaReferencia) {
        $sql = <<<'SQL'
            SELECT
                CASE
                    WHEN datos.edad < 30 THEN '<30'
                    WHEN datos.edad BETWEEN 30 AND 39 THEN '30-39'
                    WHEN datos.edad BETWEEN 40 AND 49 THEN '40-49'
                    WHEN datos.edad BETWEEN 50 AND 59 THEN '50-59'
                    ELSE '>=60'
                END AS rango_edad,
                datos.gender AS genero,
                COUNT(*) AS total_empleados
            FROM (
                SELECT
                    e.emp_no,
                    e.gender,
                    TIMESTAMPDIFF(YEAR, e.birth_date, ?) AS edad
                FROM employees AS e
                WHERE e.hire_date <= ?
                  AND EXISTS (
                      SELECT 1
                      FROM dept_emp AS de
                      WHERE de.emp_no = e.emp_no
                        AND de.from_date <= ?
                        AND de.to_date > ?
                  )
            ) AS datos
            GROUP BY rango_edad, datos.gender
            ORDER BY
                FIELD(rango_edad, '<30', '30-39', '40-49', '50-59', '>=60'),
                FIELD(datos.gender, 'F', 'M')
        SQL;

        $sentencia = $this->db->prepare($sql);
        $sentencia->execute([
            $fechaReferencia,
            $fechaReferencia,
            $fechaReferencia,
            $fechaReferencia,
        ]);

        $rangos = ['<30', '30-39', '40-49', '50-59', '>=60'];
        $distribucion = [];

        foreach ($rangos as $rango) {
            $distribucion[$rango] = [
                'Mujeres' => 0,
                'Hombres' => 0,
            ];
        }

        foreach ($sentencia->fetchAll() as $fila) {
            $genero = $fila['genero'] === 'F' ? 'Mujeres' : 'Hombres';
            $distribucion[$fila['rango_edad']][$genero] = (int) $fila['total_empleados'];
        }

        return $distribucion;
    }
}
?>
