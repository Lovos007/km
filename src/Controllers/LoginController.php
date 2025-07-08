<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\User;
use App\Models\MainModel;
use App\Views\View;

class LoginController
{
    private $userModel;
    private $MainModel;

    public function __construct()
    {
        // Crear una instancia de la clase Database y obtener la conexión PDO
        $db = (new Database())->getConnection();
        
        // Pasar la conexión al modelo User
        $this->userModel = new User($db);
        $this->MainModel = new MainModel($db);
    }

    public function cifrarPass($password){
        return  $this->userModel->cifrarContraseña($password);
        
    }

    // Mostrar el formulario de login
    public function showLogin()
    {
        View::render('login');
    }

    // Intentar autenticar al usuario
    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
           

            // Validación básica de los campos de entrada
            if (empty($username) || empty($password)) {
                View::render('login', ['error' => 'Por favor, ingrese tanto el usuario como la contraseña']);
                return;
            }

            // Intentar autenticar al usuario
            $user = $this->userModel->authenticate($username, $password);
            

            if ($user) {
                // Si la autenticación es correcta, guardar el ID del usuario en sesión
                $_SESSION['user_id'] = $user['usuario_id'];
                //echo $_SESSION['user_id'];
               // echo 'Location:'.BASE_URL.'home';
                // Redirigir a la página principal
                header('Location:'.BASE_URL.'home');
                
                
            } else {
                // Si las credenciales son incorrectas, mostrar un mensaje de error
                View::render('/login', ['error' => 'Usuario o contraseña incorrectos']);
              
                //header('Location: /login');
            }
        }
    }

    // Cerrar sesión y redirigir al login
    public function logout()
    {
        session_destroy(); // Eliminar todas las variables de sesión
        header('Location:'.BASE_URL.'login');
        exit;
    }
}
