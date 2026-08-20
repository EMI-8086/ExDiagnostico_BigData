<?php
class SalarioController {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function getDatosSalarios() {
        $sql = "SELECT d.dept_name AS departamento, ROUND(AVG(s.salary), 2) AS salario_promedio 
                FROM departments d 
                JOIN dept_emp de ON d.dept_no = de.dept_no 
                JOIN salaries s ON de.emp_no = s.emp_no 
                GROUP BY d.dept_name 
                ORDER BY salario_promedio DESC";
        $stmt = $this->db->query($sql);
        $resultados = $stmt->fetchAll();

        $datos = ['departamentos' => [], 'salarios' => []];

        foreach ($resultados as $row) {
            $datos['departamentos'][] = $row['departamento'];
            $datos['salarios'][] = $row['salario_promedio'];
        }

        return $datos;
    }
}
?>