<?php
include("bd.php");

// Obtener los datos de los trabajadores
// Seleccionar registros
$sentencia = $conexion->prepare("SELECT * FROM `trabajador`");
$sentencia->execute();
$lista_trabajadores = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Lista de Trabajadores</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
  <link rel="stylesheet" href="styles.css">
</head>

<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">FASTPACK</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Estadisticas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Lista de trabajadores</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Cerrar sesion</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
  <main>
    <div class="col-12 ">
      <div class="card transparent-card">
        <div class="card-header">
          <h1 class="card-title"><strong>LISTA DE TRABAJADORES</strong></h1>
        </div>
        <div class="card-body border-bottom py-3">
          <div class="d-flex">
            <div class="text-secondary">
              Mostrar
              <div class="mx-2 d-inline-block">
                <input type="text" class="form-control form-control-sm" value="8" size="3" aria-label="Invoices count">
              </div>
              entradas
            </div>
            <div class="ms-auto text-secondary">
              Buscar:
              <div class="ms-2 d-inline-block">
                <input type="text" class="form-control form-control-sm" aria-label="Search invoice">
              </div>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
              <tr>
                <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox"
                    aria-label="Select all invoices"></th>
                <th class="w-1">No. <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm icon-thick" width="24" height="24"
                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M6 15l6 -6l6 6" />
                  </svg>
                </th>
                <th>Nombre y Apellido</th>
                <th>Fecha de nacimiento</th>
                <th>Nacionalidad</th>
                <th>Domicilio</th>
                <th>Telefono</th>
                <th>Correo electronico</th>
                <th>Estado civil</th>
                <th>Prevision salud</th>
                <th>Acción</th>
              </tr>
            </thead>
            <tbody>
              <?php if (count($lista_trabajadores) > 0): ?>
              <?php foreach ($lista_trabajadores as $trabajador): ?>
              <tr>
                <td><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"></td>
                <td>
                  <?php echo htmlspecialchars($trabajador['id']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($trabajador['nombre_apellido']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($trabajador['fecha_nacimiento']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($trabajador['nacionalidad']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($trabajador['domicilio']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($trabajador['telefono']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($trabajador['correo_electronico']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($trabajador['estado_civil']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($trabajador['prevision_salud']); ?>
                </td>
                <td class="text-end">
                  <span class="dropdown">
                    <a class="btn dropdown-toggle align-text-top"
                      href="formulario.php?id=<?php echo $trabajador['id']; ?>" data-bs-boundary="viewport">Ver
                      ficha</a>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php else: ?>
              <tr>
                <td colspan="10">No se encontraron trabajadores.</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        <div class="card-footer d-flex align-items-center">

          <ul class="pagination m-0 ms-auto">
            <li class="page-item disabled">
              <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                  stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M15 6l-6 6l6 6" />
                </svg>
                atras
              </a>
            </li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item active"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">4</a></li>
            <li class="page-item"><a class="page-link" href="#">5</a></li>
            <li class="page-item">
              <a class="page-link" href="#">
                siguiente <!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                  stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                  <path d="M9 6l6 6l-6 6" />
                </svg>
              </a>
            </li>
          </ul>
        </div>
      </div>
  </main>
</body>
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>

</html>