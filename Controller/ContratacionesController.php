<?php
class ContratacionesController {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    public function getDatosContrataciones() {
        $sql = "SELECT YEAR(hire_date) AS anio, gender AS genero, COUNT(*) AS total_contrataciones 
                FROM employees 
                GROUP BY YEAR(hire_date), gender 
                ORDER BY anio";
        $stmt = $this->db->query($sql);
        $resultados = $stmt->fetchAll();

        $datos = ['anios' => [], 'hombres' => [], 'mujeres' => []];
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

    // Agrega este nuevo método dentro de la clase
    public function getTablaContrataciones() {
        $sql = "SELECT YEAR(hire_date) AS anio, gender AS genero, COUNT(*) AS total_contrataciones 
                FROM employees 
                GROUP BY YEAR(hire_date), gender 
                ORDER BY anio DESC"; // Ordenado del más reciente al más antiguo
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
?>