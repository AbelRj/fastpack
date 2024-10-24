<?php
include("bd.php");
$sentencia = $conexion->prepare("SELECT * FROM login");
$sentencia->execute();
$lista_usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);

include("templates/header.php") ?>

<form class="row g-3 m-0 p-4">
<div class="col-md-12">
    <label for="inputNombreApellido" class="form-label">Nombre y Apellido</label>
    <input type="text" class="form-control" id="inputNombreApellido" placeholder="Escriba aqui">
  </div>
  <div class="col-md-12">
    <label for="inputNombreUsuario" class="form-label">Nombre de Usuario</label>
    <input type="text" class="form-control" id="inputNombreUsuario" placeholder="Escriba aqui">
  </div>
  <div class="col-md-12">
    <label for="inputEmail4" class="form-label">Email</label>
    <input type="email" class="form-control" id="inputEmail4" placeholder="Escriba su correo electronico">
  </div>
  <div class="col-md-12">
    <label for="inputPassword4" class="form-label">Password</label>
    <input type="password" class="form-control" id="inputPassword4" placeholder="Escriba su contraseña">
  </div>

  </div>
  <div class="col-12">
    <button type="submit" class="btn btn-primary">Sign in</button>
  </div>
</form>
</main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>