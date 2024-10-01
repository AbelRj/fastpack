<?php 
include("bd.php");
session_start();

$error = '';

if (isset($_SESSION["usuario"]) && $_SESSION["usuario"] != null) {
  header("Location: index.php");
  exit();
}

if ($_POST) {
       
  $usuario=$_POST["usuario"];
  $contrasenia=$_POST["contrasenia"];

  $sentencia=$conexion->prepare("SELECT * FROM `login` WHERE nombre_usuario=:usuario");
  $sentencia->bindParam(":usuario",$usuario);
  $sentencia->execute();
  $usuarios=$sentencia->fetchAll();

  foreach($usuarios as $user) {
        
    if ($user["nombre_usuario"]==$usuario && $user["password"]==$contrasenia ) {
      $_SESSION["usuario"]=$user["nombre_usuario"];
      header("location:index.php");
      exit();
    }  
  }

  $error= "Usuario o contraseña incorrecta";
  //echo "<script>alert('Usuario o contraseña incorrecta'); window.location.href='login.php';</script>";
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <link rel="stylesheet" href="login.css">
    <title>Document</title>
    

    
</head>
<body  class=" d-flex flex-column">
    <script src="./dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page page-center">
      <div class="container container-tight py-4">
        <div class="text-center mb-4">
        </div>
        <div class="card card-md">
          <div class="card-body">
            <div class="contenedor-imgLogin">
                <img src="img/Logo Fastpack 4.jpg"  alt="Tabler" class="navbar-brand-image logo-login">
            </div>
            
            <form action="login.php" method="post" autocomplete="off" novalidate>
              <div class="mb-3">
                <label class="form-label">Usuario</label>
                <input type="text" name="usuario" class="form-control" placeholder="..." autocomplete="off">
              </div>
              <div class="mb-2">
                <label class="form-label">
                  Contraseña
                </label>
                <div class="input-group input-group-flat">
                  <input type="password" name="contrasenia" class="form-control"  placeholder="Your password"  autocomplete="off">
                  <span class="input-group-text">
                    <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                    </a>
                  </span>
                </div>
              </div>
              <div class="mb-2">
              </div>
              <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100 buttton-login">Iniciar sesión</button>
              </div>
            </form>
            <?php if ($error): ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="./dist/js/tabler.min.js?1692870487" defer></script>
    <script src="./dist/js/demo.min.js?1692870487" defer></script>
  </body>
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
</html>