<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("bd.php");
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php'; // Asegúrate de haber ejecutado `composer install` antes
$error = '';


if ($_POST) {
  $email = $_POST['email'];

      // Consulta para verificar si el correo existe
      $sql = "SELECT * FROM login WHERE email = ?";
      $stmt = $conexion->prepare($sql);
      $stmt->bindParam(1, $email);
      $stmt->execute();
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($user) {
        $token = bin2hex(random_bytes(50));

        $sql = "UPDATE login SET token_reset = ? WHERE email = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(1, $token);
        $stmt->bindParam(2, $email);
        $stmt->execute();

        // Configurar PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'abel.250.96@gmail.com'; // Tu dirección de correo
            $mail->Password = 'ooxd dpjx bqhz badq'; // Tu contraseña o token de aplicación de Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Remitente y destinatario
            $mail->setFrom('tu_correo@gmail.com', 'Nombre Remitente');
            $mail->addAddress($email);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Restablecimiento de password';
            $mail->Body    = "Haz clic en el siguiente enlace para restablecer tu contraseña: <a href='http://localhost/fastpack/reset.php?token=$token'>Restablecer password</a>";

            $mail->send();
            echo 'Correo de restablecimiento de contraseña enviado.';
        } catch (Exception $e) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
    } else {
        echo "Correo electrónico no encontrado.";
    }
  

  echo 'error'; // Devuelve 'error' si falló la autenticación.
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
  <link rel="stylesheet" href="css/login.css">
</head>
<body class="d-flex flex-column">
  <div class="page page-center">
    <div class="container container-tight py-4">
      <div class="text-center mb-4"></div>
      <div class="card card-md">
        <div class="card-body">
          <div class="contenedor-imgLogin">
            <img src="img/Logo Fastpack 4.jpg" alt="Logo" class="navbar-brand-image logo-login">
          </div>
          <!-- Formulario de Correo -->
          <form id="loginForm" method="post" autocomplete="off" novalidate>
            <div class="mb-3">
              <label class="form-label">Correo electronico</label>
              <input type="email"  name="email" class="form-control" placeholder="a@gmail.com" autocomplete="off">
            </div>

            <div class="form-footer">
              <button type="submit" id="submitButton" class="btn btn-login w-100" > <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
              Restablecer contraseña</button>
            </div>
          </form>


            <!-- Modal de éxito con Tabler -->
  <div id="successModal" class="modal" tabindex="-1" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Correo electronico enviado</h5>
        </div>
        <div class="modal-body">
          Se envio con exito el correo de restablecimiento de contraseña.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn buttton-modal" id="redirectButton">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

          <!-- Mensaje de error -->
          <div id="errorAlert" class="alert alert-danger mt-3" role="alert" style="display: none;">
           Correo electronico incorrecta.
          </div>
        </div>
      </div>
    </div>
  </div>
  <script>
    // Redirigir al login cuando se presione el botón "Aceptar"
    document.getElementById('redirectButton').addEventListener('click', function() {
        window.location.href = 'login.php';
    });
  </script>
</body>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
</html>


