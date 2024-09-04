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
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="styles.css"> <!-- Incluye tus estilos aquí -->
</head>
<body>
    <h1>Lista de Trabajadores</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Nombre y Apellido</th>
                <th>Fecha de Nacimiento</th>
                <th>Nacionalidad</th>
                <th>Domicilio</th>
                <th>Teléfono</th>
                <th>Correo Electrónico</th>
                <th>Estado Civil</th>
                <th>Previsión de Salud</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($lista_trabajadores) > 0): ?>
                <?php foreach ($lista_trabajadores as $trabajador): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($trabajador['nombre_apellido']); ?></td>
                        <td><?php echo htmlspecialchars($trabajador['fecha_nacimiento']); ?></td>
                        <td><?php echo htmlspecialchars($trabajador['nacionalidad']); ?></td>
                        <td><?php echo htmlspecialchars($trabajador['domicilio']); ?></td>
                        <td><?php echo htmlspecialchars($trabajador['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($trabajador['correo_electronico']); ?></td>
                        <td><?php echo htmlspecialchars($trabajador['estado_civil']); ?></td>
                        <td><?php echo htmlspecialchars($trabajador['prevision_salud']); ?></td>
                        <td><a href="formulario.php?id=<?php echo $trabajador['id']; ?>">Ver Ficha</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">No se encontraron trabajadores.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
