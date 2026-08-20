<?php
// Reporte 5: Empleados con mayor incremento salarial
class IncrementoSalarialController {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function getIncrementoSalarial($top) {
        $opcionesPermitidas = [5, 10, 20, 50];
        $limite = in_array($top, $opcionesPermitidas, true) ? $top : 10;

        $sql = <<<SQL
            SELECT
                e.emp_no,
                CONCAT(e.first_name, ' ', e.last_name) AS empleado,
                resumen.salario_minimo,
                resumen.salario_maximo,
                ROUND(
                    (
                        (resumen.salario_maximo - resumen.salario_minimo)
                        / resumen.salario_minimo
                    ) * 100,
                    2
                ) AS porcentaje_incremento,
                TIMESTAMPDIFF(
                    YEAR,
                    e.hire_date,
                    CASE
                        WHEN resumen.fecha_fin = '9999-01-01' THEN CURDATE()
                        ELSE resumen.fecha_fin
                    END
                ) AS anios_carrera
            FROM employees AS e
            INNER JOIN (
                SELECT
                    emp_no,
                    MIN(salary) AS salario_minimo,
                    MAX(salary) AS salario_maximo,
                    MAX(to_date) AS fecha_fin
                FROM salaries
                GROUP BY emp_no
                HAVING COUNT(*) > 1
                   AND MIN(salary) > 0
            ) AS resumen
                ON resumen.emp_no = e.emp_no
            ORDER BY porcentaje_incremento DESC, e.emp_no ASC
            LIMIT {$limite}
        SQL;

        $sentencia = $this->db->query($sql);
        return $sentencia->fetchAll();
    }
}
?>
