<?php
// Reporte 3: Número de empleados por departamento
// Un solo controlador dedicado a esta implementación, como pidió Emil.
class DepartamentoController {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

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
}
?>
