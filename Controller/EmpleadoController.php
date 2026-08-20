<?php

class EmpleadoController
{
    private $db;

    public function __construct($conexion)
    {
        $this->db = $conexion;
    }

    /**
     * Busca empleados por número o por nombre.
     */
    public function buscarEmpleados($busqueda)
    {
        $sql = "
            SELECT
                emp_no,
                first_name,
                last_name,
                gender,
                birth_date,
                hire_date
            FROM employees
            WHERE emp_no = :busqueda
               OR first_name LIKE :nombre
               OR last_name LIKE :apellido
            ORDER BY emp_no
            LIMIT 50
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':busqueda' => is_numeric($busqueda) ? (int)$busqueda : 0,
            ':nombre'   => '%' . $busqueda . '%',
            ':apellido' => '%' . $busqueda . '%'
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Obtiene la información completa de un empleado.
     */
    public function getEmpleado($empNo)
    {
        $sql = "
            SELECT
                emp_no,
                first_name,
                last_name,
                gender,
                birth_date,
                hire_date
            FROM employees
            WHERE emp_no = :emp_no
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':emp_no' => $empNo
        ]);

        return $stmt->fetch();
    }

    /**
     * Obtiene el historial de salarios.
     */
    public function getSalarios($empNo)
    {
        $sql = "
            SELECT
                salary,
                from_date,
                to_date
            FROM salaries
            WHERE emp_no = :emp_no
            ORDER BY from_date DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':emp_no' => $empNo
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Obtiene el historial de departamentos.
     */
    public function getDepartamentos($empNo)
    {
        $sql = "
            SELECT
                d.dept_no,
                d.dept_name,
                de.from_date,
                de.to_date
            FROM dept_emp de
            INNER JOIN departments d
                ON de.dept_no = d.dept_no
            WHERE de.emp_no = :emp_no
            ORDER BY de.from_date DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':emp_no' => $empNo
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Obtiene el historial de puestos.
     */
    public function getPuestos($empNo)
    {
        $sql = "
            SELECT
                title,
                from_date,
                to_date
            FROM titles
            WHERE emp_no = :emp_no
            ORDER BY from_date DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':emp_no' => $empNo
        ]);

        return $stmt->fetchAll();
    }
}
?>