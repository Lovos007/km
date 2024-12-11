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

   
   // Método para consultar datos con soporte para LIKE
public function consultar($tabla, $condiciones = [])
{
    $condicionesString = "";
    if (!empty($condiciones)) {
        $condicionesString = "WHERE " . implode(" AND ", array_map(function($columna, $valor) {
            // Usa LIKE si el valor contiene un comodín (%)
            return strpos($valor, '%') !== false ? "$columna LIKE :$columna" : "$columna = :$columna";
        }, array_keys($condiciones), $condiciones));
    }

    $sql = "SELECT * FROM $tabla $condicionesString";
    $stmt = $this->conexion->prepare($sql);

    // Vincula los parámetros, ajustando el formato para PDO
    foreach ($condiciones as $columna => $valor) {
        $stmt->bindValue(":$columna", $valor, PDO::PARAM_STR);
    }

    try {
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna los resultados como un array asociativo
    } catch (PDOException $e) {
        die("Error al consultar: " . $e->getMessage());
    }
}

//ejemplos
// $resultados = $miClase->consultar('usuarios', [
//     'nombre' => 'Juan',
//     'edad' => '30'
// ]);
//---------------------------------------------
// $resultados = $miClase->consultar('usuarios', [
//     'nombre' => '%Juan%',
//     'email' => '%gmail.com%'
// ]);



//Metodo para llamar al procedimiento almacenado crear perfil
public function agregarPerfil($nombrePerfil, $usuarioC, $estado)
{
    // Crear la consulta para llamar al procedimiento almacenado
    $sql = "CALL agregar_perfil(:nombre_perfil, :usuario_c, :estado)";
    $stmt = $this->conexion->prepare($sql);

    try {
        // Vincular los parámetros
        $stmt->bindValue(':nombre_perfil', $nombrePerfil, PDO::PARAM_STR);
        $stmt->bindValue(':usuario_c', $usuarioC, PDO::PARAM_INT);
        $stmt->bindValue(':estado', $estado, PDO::PARAM_INT);

        // Ejecutar el procedimiento
        $stmt->execute();

        // Recuperar el resultado del procedimiento
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Retornar el mensaje del procedimiento
        return $resultado['resultado'];
    } catch (PDOException $e) {
        die("Error al ejecutar el procedimiento almacenado 'agregar_perfil': " . $e->getMessage());
    }
}
public function mostrarPermisosPerfil($perfil_id)
{
    // Consulta SQL con JOIN entre tres tablas, filtrando por perfil_id
    $sql = "
        SELECT 
            perfiles.nombre_perfil,
            permisos.permiso_id,
            permisos.permiso,
            permisos.descripcion, 
            perfiles_permisos.estado
        FROM 
            perfiles_permisos
        JOIN 
            perfiles ON perfiles.perfil_id = perfiles_permisos.perfil_id
        JOIN 
            permisos ON permisos.permiso_id = perfiles_permisos.permiso_id
        WHERE 
            perfiles.perfil_id = :perfil_id;
    ";

    // Preparar la consulta
    $stmt = $this->conexion->prepare($sql);

    // Vincular el parámetro :perfil_id
    $stmt->bindParam(':perfil_id', $perfil_id, PDO::PARAM_INT);

    try {
        // Ejecutar la consulta
        $stmt->execute();

        // Obtener los resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retornar los resultados
        return $resultados;
    } catch (PDOException $e) {
        die("Error al realizar la consulta: " . $e->getMessage());
    }
}



}


