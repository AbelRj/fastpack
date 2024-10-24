<?php
include("bd.php");
$sentencia = $conexion->prepare("SELECT * FROM login");
$sentencia->execute();
$lista_usuarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);

include("templates/header.php") ?>
<div class="table-responsive tabla">
    <table id="table_id" class="display table table-striped table-bordered w-100">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre - Apellido</th>
                <th>Nombre Usuario</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($lista_usuarios as $usuario): ?>
                <tr>
                    
                    <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['nombre_apellido']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['nombre_usuario']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['rol']); ?></td>
                    <td class="text-end">
                        <a class="btn  btnficha" href="formulario.php?id=<?php echo $usuario['id']; ?>">Editar</a>
                        <a class="btn  btnficha" href="formulario.php?id=<?php echo $usuario['id']; ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="cont-btn-index">
    <a class="btn btn-actualizar" href="nuevoUsuario.php" role="button">Nuevo usuario</a>
</div>


</main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>