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


    public function limpiarCadena($cadena)
    {
        // 1. Eliminar etiquetas HTML y PHP
        $cadena = strip_tags($cadena);

        // 2. Convertir caracteres especiales a entidades HTML para evitar XSS
        $cadena = htmlspecialchars($cadena, ENT_QUOTES, 'UTF-8');

        // 3. Eliminar espacios en blanco adicionales
        $cadena = trim($cadena);

        // 4. Opcional: eliminar caracteres no imprimibles
        $cadena = preg_replace('/[\x00-\x1F\x7F]/u', '', $cadena);

        return $cadena;
    }

    /**
     * Limpia un array de cadenas recursivamente.
     *
     * @param array $array El array a limpiar.
     * @return array El array sanitizado.
     */
    public function limpiarArray(array $array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::limpiarArray($value); // Llamada recursiva
            } else {
                $array[$key] = self::limpiarCadena($value);
            }
        }
        return $array;
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


    public function consultar($tabla, $condiciones = [], $condicional = " AND ", $orderby = '')
    {
        $condicionesString = "";
        $parametros = []; // Para almacenar los parámetros correctamente

        if (!empty($condiciones)) {
            $condicionesString = "WHERE " . implode($condicional, array_map(function ($columna) use (&$parametros, $condiciones) {
                $valor = $condiciones[$columna];

                // Si el valor contiene '%', se usa LIKE
                if (strpos($valor, '%') !== false) {
                    $parametros[$columna] = $valor;
                    return "$columna LIKE :$columna";
                }

                // Si no, se usa "=" por defecto
                $parametros[$columna] = $valor;
                return "$columna = :$columna";
            }, array_keys($condiciones)));
        }

        // Si no hay condiciones, no poner el "WHERE"
        $sql = "SELECT * FROM $tabla" . ($condicionesString ? " $condicionesString" : "") . " $orderby";

        $stmt = $this->conexion->prepare($sql);

        // Vincula los parámetros usando el array limpio
        foreach ($parametros as $columna => $valor) {
            $stmt->bindValue(":$columna", $valor, PDO::PARAM_STR);
        }

        try {
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna los resultados como un array asociativo
        } catch (PDOException $e) {
            die("Error al consultar: " . $e->getMessage());
        }
    }

    public function consultarConCondiciones($tabla, $condiciones = '', $orderby = '')
    {
        // Construir la consulta SQL base
        $sql = "SELECT * FROM $tabla";

        // Si hay condiciones, añadirlas a la consulta
        if (!empty($condiciones)) {
            $sql .= " WHERE " . $condiciones;
        }

        // Añadir el ORDER BY si es necesario
        if (!empty($orderby)) {
            $sql .= " $orderby";
        }



        // Preparar la consulta
        $stmt = $this->conexion->prepare($sql);

        try {
            $stmt->execute(); // Ejecutar la consulta
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devolver los resultados como un array asociativo
        } catch (PDOException $e) {
            die("Error al consultar: " . $e->getMessage());
        }
    }




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
            permisos.permiso_id as permiso_id,
            permisos.permiso as permiso,
            permisos.descripcion as descripcion, 
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

    public function TablaResumenVale($vale_id)
    {
        $sql = "
     SELECT 
     tipo_gasto,
     SUM(cantidad_gasto) AS cantidad,
     AVG(monto_gasto / cantidad_gasto) AS precio_unitario,
     SUM(monto_gasto) AS monto
     FROM 
     vale_detalle
     WHERE 
     vale_id = :vale_id
     GROUP BY 
     tipo_gasto
     ORDER BY 
     tipo_gasto;
     ";
        // Preparar la consulta
        $stmt = $this->conexion->prepare($sql);

        // Vincular el parámetro :perfil_id
        $stmt->bindParam(':vale_id', $vale_id, PDO::PARAM_INT);
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


