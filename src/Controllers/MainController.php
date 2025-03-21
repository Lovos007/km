<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\MainModel;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

final class MainController 
{
    private $MainModel;

    public function __construct()
    {
        // Crear una instancia de la clase Database y obtener la conexión PDO
        $conexion = (new Database())->getConnection();
        $this->MainModel = new MainModel($conexion);
    }

    public function getEmpresas($search = '')
    {
        if ($search == '') {
            // Si se encontró el perfiles, lo devuelve, de lo contrario, retorna un array vacío.
            $empresas = $this->MainModel->consultar('empresas');
            return $empresas ? $empresas : [];

        } else {
            $datos =
                [
                    'empresa' => '%' . $search . '%'
                ];

            $vechiculos = $this->MainModel->consultar('empresas', $datos);
            return $vechiculos ? $vechiculos : [];

        }
    }

    
    public function enviarCorreo($destinatario, $asunto, $mensaje)
    {
        require 'vendor/autoload.php'; // Asegúrate de tener PHPMailer instalado con Composer

        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP (Gmail en este caso)
            $mail->SMTPAuth = true;
            $mail->Username = 'notificaciones.km.sv@gmail.com'; // Tu correo
            $mail->Password = 'qlhv qwjq pkmh ixkn'; // Usa una contraseña de aplicación si usas Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Remitente y destinatario
            $mail->setFrom('notificaciones.km.sv@gmail.com', 'APP KM');
            $mail->addAddress($destinatario);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body = $mensaje;
            $mail->AltBody = strip_tags($mensaje); // Versión sin HTML

            // Enviar correo
            $mail->send();
            return ['status' => 'success', 'message' => 'Correo enviado correctamente'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => "Error al enviar el correo: {$mail->ErrorInfo}"];
        }
    }

}

