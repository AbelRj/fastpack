<?php
include("bd.php");
session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verifica si el token es válido
    $sql = "SELECT * FROM login WHERE token_reset = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(1, $token);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nueva_contrasenia = password_hash($_POST['nueva_contrasenia'], PASSWORD_DEFAULT);

            // Actualizar la contraseña en la base de datos
            $sql = "UPDATE login SET password = ?, token_reset = NULL WHERE token_reset = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(1, $nueva_contrasenia);
            $stmt->bindParam(2, $token);
            if ($stmt->execute()) {
                // Mostrar modal de éxito con Tabler
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        let modal = document.getElementById('successModal');
                        let backdrop = document.getElementById('modalBackdrop');
                        backdrop.style.display = 'block';  // Mostrar fondo opaco
                        modal.style.display = 'block';    // Mostrar modal
                    });
                </script>";
            } else {
                echo "Error al actualizar la contraseña.";
            }
        }
    } else {
        echo "Token no válido o ya usado.";
    }
} else {
    echo "Token no válido.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
  <link rel="stylesheet" href="css/login.css">
  <style>
    /* Fondo opaco cuando el modal está visible */
    #modalBackdrop {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5); /* Opacidad del 50% */
      display: none; /* Oculto por defecto */
      z-index: 1040;
    }

    /* Añadir borde al modal */
    .modal-content {

      border-radius: 8px; /* Bordes redondeados */
    }

    /* Alinear modal al centro */
    .modal {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 1050;
    }

    .buttton-modal{
        background-color: #C71585;
        color: #fff;
    }
    .buttton-modal:hover{
        background-color: #00BFFF;
    }
  </style>
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
          <!-- Formulario de cambio de contraseña -->
          <form method="post" autocomplete="off" novalidate>
            <div class="mb-3">
              <label class="form-label">Nueva contraseña</label>
              <input type="password" id="usuarioInput" name="nueva_contrasenia" class="form-control" placeholder="..." autocomplete="off">
            </div>
            <div class="form-footer">
              <button type="submit" id="submitButton" class="btn btn-login w-100"> 
              Cambiar contraseña</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Fondo opaco -->
  <div id="modalBackdrop"></div>

  <!-- Modal de éxito con Tabler -->
  <div id="successModal" class="modal" tabindex="-1" style="display: none;">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Contraseña cambiada</h5>
        </div>
        <div class="modal-body">
          Su contraseña ha sido cambiada exitosamente. Presione "Aceptar" para continuar al login.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn buttton-modal" id="redirectButton">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>

  <script>
    // Redirigir al login cuando se presione el botón "Aceptar"
    document.getElementById('redirectButton').addEventListener('click', function() {
        window.location.href = 'login.php';
    });
  </script>
</body>
</html>
