<?php
include("bd.php");

// Obtener los trabajadors de los trabajadores
// Seleccionar registros
$sentencia = $conexion->prepare("SELECT * FROM `trabajador`");
$sentencia->execute();
$lista_trabajadores = $sentencia->fetchAll(PDO::FETCH_ASSOC);
include("templates/header.php") ?>

<div class="cont-button">
    <form action="index.php" method="post" enctype="multipart/form-data">
        <input type="text" placeholder="Buscar..." name="buscar">
        <button type="submit" class="boton-buscar">Buscar</button>
        <div class="radio-group">
                <label>
                    <input type="radio" name="option" value="rut" checked>
                    Rut
                </label>
                <label>
                    <input type="radio" name="option" value="email">
                    Email
                </label>
        </div>
    </form>
</div>
<div class="contenedor">
    <table>
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
        <?php if($_POST){

try {

        $buscar=$_POST["buscar"];
        $option=$_POST["option"];
        switch ($option) {
            case "rut":
                $sql="SELECT * FROM `trabajador` WHERE `rut`=:buscar";
                $stmt =$conexion->prepare($sql);
                $stmt->bindParam(':buscar', $buscar, PDO::PARAM_STR);
                $stmt->execute();
                
                $rows = $stmt->fetchAll();
                break;
            case "email":
                $sql="SELECT * FROM `trabajador` WHERE `correo_electronico`=:buscar";
                $stmt =$conexion->prepare($sql);
                $stmt->bindParam(':buscar', $buscar, PDO::PARAM_STR);
                $stmt->execute();
                
                $rows = $stmt->fetchAll();
                break;
            case "celular":
                $sql="SELECT * FROM `trabajador` WHERE `celular`=:buscar";
                $stmt =$conexion->prepare($sql);
                $stmt->bindParam(':buscar', $buscar, PDO::PARAM_STR);
                $stmt->execute();
                
                $rows = $stmt->fetchAll();
                break;
            case "apellidoPaterno":
                $sql="SELECT * FROM `trabajador` WHERE `nombre_apellido`=:buscar";
                $stmt =$conexion->prepare($sql);
                $stmt->bindParam(':buscar', $buscar, PDO::PARAM_STR);
                $stmt->execute();
                
                $rows = $stmt->fetchAll();
                break;
        }
         foreach ($rows as $row) {?>
            <tr>
            <td class="text-end">
                  <span class="dropdown">
                    <a class="btn dropdown-toggle align-text-top"
                      href="formulario.php?id=<?php echo $row['id']; ?>" data-bs-boundary="viewport">Ver
                      ficha</a>
                  </span>
                </td>
            <td> <?php print_r($row['id']); ?> </td>
            <td> <?php print_r($row['rut']);?> </td>
            <td> <?php print_r($row['nombre_apellido']);?> </td>
            <td> <?php print_r($row['sexo']);?> </td>
            <td> <?php print_r($row['fecha_nacimiento']);?> </td>
            <td> <?php print_r($row['nacionalidad']);?> </td>
            <td> <?php print_r($row['profesion']);?> </td>
            <td> <?php print_r($row['domicilio']);?> </td>
            <td> <?php print_r($row['telefono']);?> </td>
            <td> <?php print_r($row['celular']);?> </td>
            <td> <?php print_r($row['correo_electronico']);?> </td>
            <td> <?php print_r($row['estado_civil']);?> </td>
            <td> <?php print_r($row['prevision_salud']);?> </td>
            
                <?php } ?>
                      
            </tr>

<?php } catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}

}else{

foreach( $lista_trabajadores as $trabajador){?>

<tr>
<td class="text-end">
                  <span class="dropdown">
                    <a class="btn dropdown-toggle align-text-top"
                      href="formulario.php?id=<?php echo $trabajador['id']; ?>" data-bs-boundary="viewport">Ver
                      ficha</a>
                  </span>
                </td>
    <td> <?php print_r($trabajador['id']); ?> </td>
    <td> <?php print_r($trabajador['rut']);?> </td>
    <td> <?php print_r($trabajador['nombre_apellido']);?> </td>
    <td> <?php print_r($trabajador['sexo']);?> </td>
    <td> <?php print_r($trabajador['fecha_nacimiento']);?> </td>
    <td> <?php print_r($trabajador['nacionalidad']);?> </td>
    <td> <?php print_r($trabajador['profesion']);?> </td>
    <td> <?php print_r($trabajador['domicilio']);?> </td>
    <td> <?php print_r($trabajador['telefono']);?> </td>
    <td> <?php print_r($trabajador['celular']);?> </td>
    <td> <?php print_r($trabajador['correo_electronico']);?> </td>
    <td> <?php print_r($trabajador['estado_civil']);?> </td>
    <td> <?php print_r($trabajador['prevision_salud']);?> </td>

</tr>

<?php  } 

};?>
            </tbody>
              </table>
</div>
    <form action="guardar.php" method="post">
        <input class="form-control" type="hidden"  name="txtTiempo" id="" value="1"><br/>
        <button type="submit" class="boton-buscar">Guardar / Actualizar BD</button>
    </form>
      <?php include("templates/footer.php") ?>
</body>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>

</html>