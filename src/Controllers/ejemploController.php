<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\User;



// try {
    
//     // Instanciar la clase
//     {
//         // Crear una instancia de la clase Database y obtener la conexión PDO
//         $db = (new Database())->getConnection();
        
//         // Pasar la conexión al modelo User
//         $this->userModel = new User($db);
//     }
//     // Insertar un registro
//     $nuevoId = $db->insertar('usuarios', [
//         'nombre' => 'Juan',
//         'email' => 'juan@example.com'
//     ]);
//     echo "Nuevo registro con ID: $nuevoId\n";

//     // Actualizar un registro
//     $filasAfectadas = $db->actualizar('usuarios', ['nombre' => 'Juan Pérez'], ['id' => $nuevoId]);
//     echo "Registros actualizados: $filasAfectadas\n";

//     // Consultar registros
//     $usuarios = $db->consultar('usuarios', ['id' => $nuevoId]);
//     print_r($usuarios);

//     // Eliminar un registro
//     $filasEliminadas = $db->eliminar('usuarios', ['id' => $nuevoId]);
//     echo "Registros eliminados: $filasEliminadas\n";

// } catch (PDOException $e) {
//     die("Error en la conexión: " . $e->getMessage());
// }
