<?php
include("bd.php");

// Obtener los trabajadores
//$sentencia = $conexion->prepare("SELECT * FROM [trabajador]");
$sentencia = $conexion->prepare("SELECT * FROM trabajador");
$sentencia->execute();
$lista_trabajadores = $sentencia->fetchAll(PDO::FETCH_ASSOC);
include("templates/header.php") ?>

<script>
    $(document).ready(function() {
        $('#table_id').DataTable({
            "language": {
                "sEmptyTable": "No hay datos disponibles en la tabla",
                "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
                "sInfoEmpty": "Mostrando 0 a 0 de 0 entradas",
                "sInfoFiltered": "(filtrado de _MAX_ entradas totales)",
                "sLengthMenu": "Mostrar _MENU_ entradas",
                "sLoadingRecords": "Cargando...",
                "sProcessing": "Procesando...",
                "sSearch": "Buscar:",
                "sZeroRecords": "No se encontraron resultados",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": activar para ordenar la columna de manera descendente"
                }
            },
           // "scrollX": true,  // Habilitar el desplazamiento horizontal
            "autoWidth": true,  // Ajuste automático del ancho de las columnas
            "responsive": true, // Hacer la tabla adaptable
            "pageLength": 5,   // Mostrar 5 filas por página
            "lengthMenu": [5, 10, 20, 30],  // Opciones para cambiar el número de filas mostradas
        });
    });
</script>

<div class="table-responsive tabla">
    <table id="table_id" class="display table table-striped table-bordered w-100">
        <thead>
            <tr>
                <th>Acción</th>
                <th>Id</th>
                <th>Rut</th>
                <th>Nombre-Apellido</th>
                <th>Sexo</th>
                <th>Fecha-nacimiento</th>
                <th>País</th>
                <th>Profesion</th>
                <th>Domicilio</th>
                <th>Teléfono</th>
                <th>Celular</th>
                <th>Correo electrónico</th>
                <th>Estado civil</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($lista_trabajadores as $trabajador): ?>
                <tr>
                    <td class="text-end">
                        <a class="btn  btnficha" href="formulario.php?id=<?php echo $trabajador['id']; ?>">Ver ficha</a>
                    </td>
                    <td><?php echo htmlspecialchars($trabajador['id']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['rut']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['nombre_apellido']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['sexo']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['fecha_nacimiento']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['nacionalidad']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['profesion']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['domicilio']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['telefono']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['celular']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['correo_electronico']); ?></td>
                    <td><?php echo htmlspecialchars($trabajador['estado_civil']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="cont-btn-index">
    <a class="btn btn-actualizar" href="api.php" role="button">Actualizar</a>
    <a class="btn btn-excel" href="exportar_excel.php" role="button">Exportar a Excel</a>
</div>


</main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
