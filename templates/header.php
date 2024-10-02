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
  <title>Lista de Trabajadores</title>
  <link rel="stylesheet" href="index.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="header.css">
  
  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
    crossorigin="anonymous"></script>
  <!-- DataTables JBootstrap -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css">
  <!-- DataTables JS library -->
  <script type="text/javascript" charset="utf8"
    src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>


</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.php">FASTPACK</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="./estadistica.php">Estadisticas</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./index.php">Lista de trabajadores</a>
            </li>
          </ul>

          <a class="nav-item">
            <a class="nav-link" href="./cerrarSesion.php">Cerrar sesion</a>
          </a>

        </div>
      </div>
    </nav>
  </header>



  <main>