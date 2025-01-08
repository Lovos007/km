<?php

namespace App\Models;

use PDO;
use \PDOException;
use \Exception; // 

class User
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Método para autenticar usuario
    public function authenticate($username, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE nombre_usuario = :username AND 
        estado = 1
         LIMIT 1");
        $stmt->bindParam(':username', $username);

        $stmt->execute();


        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = null; // Libera el recurso del statement

        if ($user && password_verify($password, $user['password'])) {
            // Autenticación exitosa
            return $user;
        } else {
            return false;
        }



    }


    public function cifrarTodasLasContraseñas()
    {
        $stmt = $this->db->query("SELECT usuario_id, password FROM usuarios");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Verificar si la contraseña ya está cifrada
            if (password_get_info($row['password'])['algo']) {
                // Si ya está cifrada, pasar a la siguiente
                continue;
            }

            // Cifrar la contraseña en texto plano
            $hashedPassword = password_hash($row['password'], PASSWORD_DEFAULT);

            // Actualizar la base de datos con la contraseña cifrada
            $updateStmt = $this->db->prepare(
                "UPDATE usuarios SET password = :hashedPassword WHERE usuario_id = :id"
            );
            $updateStmt->execute([
                ':hashedPassword' => $hashedPassword,
                ':id' => $row['usuario_id']
            ]);
        }
    }

    public function cifrarContraseña($password)
    {
        
            // Verificar si la contraseña ya está cifrada
            if (password_get_info($password)['algo']) {
                // Si ya está cifrada, pasar a la siguiente
                die("La contraseña ya se encuentra cifrada");
            }

            // Cifrar la contraseña en texto plano
            $password = password_hash($password, PASSWORD_DEFAULT);
            
            return $password;

        
    }

    public function obtenerPermiso($usuario_id, $permiso_id)
    {
        // Consulta para obtener el perfil del usuario
        $stmt = $this->db->prepare("SELECT perfil_id FROM usuarios WHERE usuario_id = :usuario_id LIMIT 1");
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $perfil_id = $user["perfil_id"];

            // Consulta para verificar el permiso
            $query = $this->db->prepare(
                "SELECT estado 
                 FROM perfiles_permisos 
                 WHERE perfil_id = :perfil_id AND permiso_id = :permiso_id LIMIT 1"
            );
            $query->bindParam(':perfil_id', $perfil_id); // Perfil del usuario
            $query->bindParam(':permiso_id', $permiso_id); // Permiso específico
            $query->execute();

            $permiso = $query->fetch(PDO::FETCH_ASSOC);

            // Verificar si el permiso está activo
            if ($permiso && $permiso['estado'] != 0) {
                return true;
            }
            return false;
        }

        // Si no se encuentra el permiso o no está activo, retornar false
        return false;
    }

    public function crearBitacora($permiso_id, $usuario_id, $referencia)
    {
        try {
            // Consulta para insertar el registro en la bitácora
            $query = $this->db->prepare(
                "INSERT INTO bitacora (permiso_id, usuario_id, referencia) 
             VALUES (:permiso_id, :usuario_id, :referencia)"
            );

            // Enlace de parámetros con los valores proporcionados
            $query->bindParam(':permiso_id', $permiso_id, PDO::PARAM_INT); // Permiso específico
            $query->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT); // Usuario específico
            $query->bindParam(':referencia', $referencia, PDO::PARAM_STR); // Referencia (documento, etc.)

            // Ejecutar la consulta
            $query->execute();
        } catch (PDOException $e) {
            // Manejo de errores: registro en logs o mostrar un mensaje personalizado
            error_log("Error al insertar en la bitácora: " . $e->getMessage());
            throw new Exception("No se pudo registrar la operación en la bitácora.");
        }
    
    }

    public function getUsuario($id){
         // Consulta para obtener el perfil del usuario
         $stmt = $this->db->prepare("SELECT usuarios.*, perfiles.nombre_perfil  FROM usuarios 
         INNER JOIN perfiles ON usuarios.perfil_id = perfiles.perfil_id
         WHERE usuario_id = :usuario_id");
         $stmt->bindParam(':usuario_id', $id);
         $stmt->execute();
 
         $user = $stmt->fetch(PDO::FETCH_ASSOC);
 
         if ($user) {
            return $user;
         }
         return false;
        
    }

    public function getUsuarios($search = "") {
        // Si no se proporciona búsqueda, obtenemos todos los usuarios
        if ($search == "") {
            $stmt = $this->db->prepare("
                SELECT usuarios.*, perfiles.nombre_perfil 
                FROM usuarios
                INNER JOIN perfiles ON usuarios.perfil_id = perfiles.perfil_id");
        } else {
            // Si se proporciona una búsqueda, usamos LIKE con % para búsqueda parcial
            $stmt = $this->db->prepare("
                SELECT  u.* ,p.nombre_perfil
                FROM usuarios AS u
                JOIN perfiles AS p ON u.perfil_id = p.perfil_id
                WHERE u.nombre_usuario LIKE :search OR u.nombre_apellido LIKE :search");
            
            // Asegúrate de incluir los comodines '%' alrededor de la búsqueda
            $searchTerm = "%" . $search . "%";
            
            // Bindear el parámetro :search correctamente
            $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        }
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Devolver los resultados
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

}

