<?php


namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmación() {
        // Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];// Credenciales propias de mi usuario photo.lisandro.ar@gmail.com proporcionado por mailtrap
        $mail->Password = $_ENV['EMAIL_PASS'];// Credenciales propias de mi usuario photo.lisandro.ar@gmail.com proporcionado por mailtrap

        $mail->setFrom('cuentas@appsalon.com', 'Mailer');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Confirma tu cuenta';

        // Set HTML. Aquí le vamos a decir que vamos a utilizar HTML en el cuerpo del E-Mail
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        // Contenido del E-Mail
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en App Salon, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p>Presiona Aquí: <a href='" . $_ENV['APP_URL'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta</a> </p>";
        $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar el E-Mail
        $mail->send();
    }

    public function enviarInstrucciones() {
        // Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];// Credenciales propias de mi usuario photo.lisandro.ar@gmail.com proporcionado por mailtrap
        $mail->Password = $_ENV['EMAIL_PASS'];// Credenciales propias de mi usuario photo.lisandro.ar@gmail.com proporcionado por mailtrap

        $mail->setFrom('cuentas@appsalon.com', 'Mailer');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Restablece tu Password';

        // Set HTML. Aquí le vamos a decir que vamos a utilizar HTML en el cuerpo del E-Mail
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        // Contenido del E-Mail
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado restablecer tu password, presiona en el siguiente enlace para hacerlo</p>";
        $contenido .= "<p>Presiona Aquí: <a href='" . $_ENV['APP_URL'] . "/recuperar?token=" . $this->token . "'>Restablecer Password</a> </p>";
        $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar el mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar el E-Mail
        $mail->send();
    }
}