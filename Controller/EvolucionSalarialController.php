<?php
// Reporte 6: Evolución anual del salario promedio (consulta nueva del equipo)
class EvolucionSalarialController {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function getEvolucionSalarial() {
        $sql = <<<'SQL'
            SELECT
                YEAR(s.from_date) AS anio,
                ROUND(AVG(s.salary), 2) AS promedio_general,
                ROUND(
                    AVG(CASE WHEN e.gender = 'F' THEN s.salary END),
                    2
                ) AS promedio_mujeres,
                ROUND(
                    AVG(CASE WHEN e.gender = 'M' THEN s.salary END),
                    2
                ) AS promedio_hombres,
                ROUND(
                    (
                        (
                            AVG(CASE WHEN e.gender = 'M' THEN s.salary END)
                            - AVG(CASE WHEN e.gender = 'F' THEN s.salary END)
                        )
                        / NULLIF(
                            AVG(CASE WHEN e.gender = 'F' THEN s.salary END),
                            0
                        )
                    ) * 100,
                    2
                ) AS brecha_porcentual,
                COUNT(*) AS registros_salariales
            FROM salaries AS s
            INNER JOIN employees AS e
                ON e.emp_no = s.emp_no
            GROUP BY YEAR(s.from_date)
            ORDER BY anio ASC
        SQL;

        $sentencia = $this->db->query($sql);
        return $sentencia->fetchAll();
    }
}
?>
