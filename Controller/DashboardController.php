<?php
class DashboardController {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    // Método para la Consulta 1
    public function getDatosContrataciones() {
        $sql = "SELECT YEAR(hire_date) AS anio, gender AS genero, COUNT(*) AS total_contrataciones 
                FROM employees 
                GROUP BY YEAR(hire_date), gender 
                ORDER BY anio";
        $stmt = $this->db->query($sql);
        $resultados = $stmt->fetchAll();

        $datos = [
            'anios' => [],
            'hombres' => [],
            'mujeres' => []
        ];
        $temp_data = [];

        foreach ($resultados as $row) {
            $anio = $row['anio'];
            $genero = $row['genero'];
            
            if (!isset($temp_data[$anio])) {
                $temp_data[$anio] = ['M' => 0, 'F' => 0];
            }
            $temp_data[$anio][$genero] = $row['total_contrataciones'];
        }

        foreach ($temp_data as $anio => $totales) {
            $datos['anios'][] = $anio;
            $datos['hombres'][] = $totales['M'];
            $datos['mujeres'][] = $totales['F'];
        }

        return $datos;
    }

    // Método para la Consulta 2
    public function getDatosSalarios() {
        $sql = "SELECT d.dept_name AS departamento, ROUND(AVG(s.salary), 2) AS salario_promedio 
                FROM departments d 
                JOIN dept_emp de ON d.dept_no = de.dept_no 
                JOIN salaries s ON de.emp_no = s.emp_no 
                GROUP BY d.dept_name 
                ORDER BY salario_promedio DESC";
        $stmt = $this->db->query($sql);
        $resultados = $stmt->fetchAll();

        $datos = [
            'departamentos' => [],
            'salarios' => []
        ];

        foreach ($resultados as $row) {
            $datos['departamentos'][] = $row['departamento'];
            $datos['salarios'][] = $row['salario_promedio'];
        }

        return $datos;
    }

    // Método para la Consulta 3
    public function getEmpleadosDepartamento() {
        $sql = <<<'SQL'
            SELECT
                d.dept_no,
                d.dept_name AS departamento,
                COUNT(DISTINCT de.emp_no) AS total_empleados
            FROM departments AS d
            LEFT JOIN dept_emp AS de
                ON de.dept_no = d.dept_no
                AND de.to_date = '9999-01-01'
            GROUP BY d.dept_no, d.dept_name
            ORDER BY total_empleados DESC
        SQL;

        $sentencia = $this->db->query($sql);
        return $sentencia->fetchAll();
    }

    // Método para la Consulta 4
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

    // Método para la Consulta 5
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

    // Método para la Consulta 6
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
