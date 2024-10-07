<?php
include("bd.php");
session_start();

$error = '';

if (isset($_SESSION["usuario"]) && $_SESSION["usuario"] != null) {
  header("Location: index.php");
  exit();
}

if ($_POST) {
  $usuario = $_POST["usuario"];
  $contrasenia = $_POST["contrasenia"];

  $sentencia = $conexion->prepare("SELECT * FROM [login] WHERE nombre_usuario=:usuario");
  $sentencia->bindParam(":usuario", $usuario);
  $sentencia->execute();
  $usuarios = $sentencia->fetchAll();

  foreach ($usuarios as $user) {
    if ($user["nombre_usuario"] == $usuario && $user["password"] == $contrasenia) {
      $_SESSION["usuario"] = $user["nombre_usuario"];
      echo 'index.php'; // Devuelve la URL si la autenticación es correcta.
      exit();
    }
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
  <link rel="stylesheet" href="login.css">
  <title>Login</title>
</head>
<body class="d-flex flex-column">
  <div class="page page-center">
    <div class="container container-tight py-4">
      <div class="text-center mb-4"></div>
      <h1>PLATAFORMA DE BIENESTAR </h1>
      <div class="card card-md">
        <div class="card-body">
          <div class="contenedor-imgLogin">
            <img src="img/Logo Fastpack 4.jpg" alt="Logo" class="navbar-brand-image logo-login">
          </div>
          <!-- Formulario de Login -->
          <form id="loginForm" action="login.php" method="post" autocomplete="off" novalidate>
            <div class="mb-3">
              <label class="form-label">Usuario</label>
              <input type="text" id="usuarioInput" name="usuario" class="form-control" placeholder="usuario" autocomplete="off">
            </div>
            <div class="mb-2">
              <label class="form-label">Contraseña</label>
              <div class="input-group input-group-flat">
                <input type="password" id="contraseniaInput" name="contrasenia" class="form-control" placeholder="contraseña" autocomplete="off">
                <span class="input-group-text">
                  <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                      <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                      <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                    </svg>
                  </a>
                </span>
              </div>
            </div>

            <div class="form-footer">
              <button type="submit" id="submitButton" class="btn btn-primary w-100 buttton-login" disabled>Iniciar sesión</button>
            </div>
          </form>

          <!-- Mensaje de error -->
          <div id="errorAlert" class="alert alert-danger mt-3" role="alert" style="display: none;">
            Usuario o contraseña incorrecta.
          </div>
        </div>
      </div>
    </div>
  </div>

</body>


<!-- Script JavaScript para manejar el login y la habilitación del botón -->
<script>
  // Referencias a los inputs y botón de submit
  const usuarioInput = document.getElementById('usuarioInput');
  const contraseniaInput = document.getElementById('contraseniaInput');
  const submitButton = document.getElementById('submitButton');

  // Función que verifica si ambos campos están llenos
  function checkInputs() {
    if (usuarioInput.value.trim() !== '' && contraseniaInput.value.trim() !== '') {
      submitButton.disabled = false; // Habilitar el botón
    } else {
      submitButton.disabled = true; // Deshabilitar el botón
    }
  }

  // Escuchar cambios en los inputs para verificar si están llenos
  usuarioInput.addEventListener('input', checkInputs);
  contraseniaInput.addEventListener('input', checkInputs);

  // Manejo del formulario al enviarse
  document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Previene que se recargue la página.

    var formData = new FormData(this); // Recoge los datos del formulario.

    fetch('login.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(data => {
      if (data.trim() === 'index.php') {
        window.location.href = 'index.php'; // Redirige si la autenticación es exitosa.
      } else if (data.trim() === 'error') {
        document.getElementById('errorAlert').style.display = 'block'; // Muestra la alerta si la autenticación falla.
      }
    })
    .catch(error => console.error('Error:', error));
  });
</script>
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
</html>


