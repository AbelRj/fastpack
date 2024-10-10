<?php
session_start();

if (isset($_SESSION["usuario"])) {
   // echo "Usuario Activo: " .$_SESSION["usuario"];
}else {
    header("location:login.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lista de Trabajadores</title>
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="templates/header.css">
  <link rel="stylesheet" href="css/estadistica.css">
    <!-- Otros enlaces CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
    crossorigin="anonymous"></script>
  <!-- DataTables JS library -->
  <script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>


</head>

<body>
<header class="border">
  <nav class="navbar navbar-expand-lg  px-3  color-fondo-herader p-0 ">
    <div class="container-fluid p-0 m-0">
      <a class="navbar-brand" href="index.php">
        <img src="img/Logo Fastpack_01.png" alt="Logo Fastpack" class="img-fluid logo-navbar">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse p-0 m-0" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="estadistica.php">Estadísticas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="index.php">Lista de trabajadores</a>
          </li>
        </ul>
        <a class="nav-item cerrar-sesion">
          <a class="nav-link " href="./cerrarSesion.php">Cerrar sesión</a>
        </a>
      </div>
    </div>
  </nav>
</header>




  <main>