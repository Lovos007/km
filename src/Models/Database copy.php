<?php

// namespace App\Models;

// use PDO;
// use PDOException;

// class Database
// {
//     private $host = 'sdb-83.hosting.stackcp.net';    // Dirección del servidor de la base de datos
//     private $dbname = 'km_app-3530393440e8';         // Nombre de la base de datos
//     private $username = 'LUIS-90cd';     // Usuario de la base de datos
//     private $password = 'abcd12XZFT';         // Contraseña de la base de datos
//     private $charset = 'utf8mb4';   // Juego de caracteres
//     private $pdo = null;            // Objeto PDO, inicialmente null

//     /**
//      * Obtiene la conexión PDO a la base de datos.
//      * @return PDO|null
//      */
//     public function getConnection()
//     {
//         if ($this->pdo === null) {
//             try {
//                 $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
//                 $this->pdo = new PDO($dsn, $this->username, $this->password);
//                 $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//                 $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Opcional: configuración de fetch
//                 $this->pdo->exec("SET time_zone = '-06:00'"); // Hora de El Salvador
//             } catch (PDOException $e) {
//                 error_log("Error al conectar a la base de datos: " . $e->getMessage());               
//                 die("Error de conexión. Por favor, intente más tarde.");
                
//             }
//         }

//         return $this->pdo;
//     }

//     /**
//      * Cierra la conexión PDO.
//      */
//     public function closeConnection()
//     {
//         $this->pdo = null;
//     }
// }
