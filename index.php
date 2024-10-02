<?php
include("bd.php");

// Obtener los trabajadors de los trabajadores
// Seleccionar registros
$sentencia = $conexion->prepare("SELECT * FROM `trabajador`");
$sentencia->execute();
$lista_trabajadores = $sentencia->fetchAll(PDO::FETCH_ASSOC);
include("templates/header.php") ?>
<script>
        $(document).ready( function () {
            $('#table_id').DataTable(
                {
                "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json"
                }
                }
            );}
            );
    </script>
<table id="table_id" class="display">
    <thead>
        <tr>
        <th>Acción</th>
                <th>Id</th>
                <th>Rut</th>
                <th>Nombre y Apellido</th>
                <th>Sexo</th>
                <th>Fecha de nacimiento</th>
                <th>Nacionalidad</th>
                <th>Profesion</th>
                <th>Domicilio</th>
                <th>Telefono</th>
                <th>Celular</th>
                <th>Correo electronico</th>
                <th>Estado civil</th>
                <th>Prevision salud</th>
                
        </tr>
    </thead>
    <tbody>
        <?php foreach($lista_trabajadores as $trabajador): ?>
            <tr>
                <td class="text-end">
                  <span class="dropdown">
                    <a class="btn dropdown-toggle align-text-top"
                      href="formulario.php?id=<?php echo $trabajador['id']; ?>" data-bs-boundary="viewport">Ver
                      ficha</a>
                  </span>
                </td>
            <td><?php echo htmlspecialchars($trabajador['id']); ?></td>
                <td>
                  <?php echo htmlspecialchars($trabajador['rut']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($trabajador['nombre_apellido']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($trabajador['sexo']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($trabajador['fecha_nacimiento']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($trabajador['nacionalidad']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($trabajador['profesion']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($trabajador['domicilio']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($trabajador['telefono']); ?>
                </td>
                <td>
                  <?php echo htmlspecialchars($trabajador['celular']); ?>
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
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>