<?php

namespace App\Models;
use PDO;
use \PDOException;

class MainModel
{
    private $conexion;

    // Constructor que recibe una conexión a la base de datos
    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    // Método para insertar datos

    public function insertar($tabla, $datos)
    {
        $columnas = implode(", ", array_keys($datos)); // Construye la lista de columnas
        $valores = ":" . implode(", :", array_keys($datos)); // Construye los placeholders

        $sql = "INSERT INTO $tabla ($columnas) VALUES ($valores)";
        $stmt = $this->conexion->prepare($sql); // Prepara la consulta

        try {
            $stmt->execute($datos); // Ejecuta la consulta con los datos
            return $stmt->rowCount(); // Retorna la cantidad de registros insertados
        } catch (PDOException $e) {
            die("Error al insertar: " . $e->getMessage()); // Maneja el error
        }
    }


    // Método para actualizar datos
    public function actualizar($tabla, $datos, $condiciones)
    {
        $set = [];
        foreach ($datos as $columna => $valor) {
            $set[] = "$columna = :$columna";
        }
        $setString = implode(", ", $set);

        $condicionesString = implode(" AND ", array_map(fn($columna) => "$columna = :cond_$columna", array_keys($condiciones)));

        $sql = "UPDATE $tabla SET $setString WHERE $condicionesString";
        $stmt = $this->conexion->prepare($sql);

        // Combina datos y condiciones en un solo array para el bind
        $parametros = array_merge($datos, array_combine(
            array_map(fn($columna) => "cond_$columna", array_keys($condiciones)),
            array_values($condiciones)
        ));

        try {
            $stmt->execute($parametros);
            return $stmt->rowCount(); // Retorna la cantidad de filas afectadas
        } catch (PDOException $e) {
            die("Error al actualizar: " . $e->getMessage());
        }
    }

    // Método para eliminar datos
    public function eliminar($tabla, $condiciones)
    {
        $condicionesString = implode(" AND ", array_map(fn($columna) => "$columna = :$columna", array_keys($condiciones)));

        $sql = "DELETE FROM $tabla WHERE $condicionesString";
        $stmt = $this->conexion->prepare($sql);

        try {
            $stmt->execute($condiciones);
            return $stmt->rowCount(); // Retorna la cantidad de filas eliminadas
        } catch (PDOException $e) {
            die("Error al eliminar: " . $e->getMessage());
        }
    }

    // Método para consultar datos
    public function consultar($tabla, $condiciones = [])
    {
        $condicionesString = "";
        if (!empty($condiciones)) {
            $condicionesString = "WHERE " . implode(" AND ", array_map(fn($columna) => "$columna = :$columna", array_keys($condiciones)));
        }

        $sql = "SELECT * FROM $tabla $condicionesString";
        $stmt = $this->conexion->prepare($sql);

        try {
            $stmt->execute($condiciones);
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna los resultados como un array asociativo
        } catch (PDOException $e) {
            die("Error al consultar: " . $e->getMessage());
        }
    }
}
