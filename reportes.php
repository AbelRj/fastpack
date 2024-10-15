<?php 
ob_start();
include("bd.php");
if(isset($_GET['id'])){
    //Identificación del Trabajador - Recuperar los datos del ID correspondiente ( seleccionado )
$idTrabajador=(isset($_GET['id']))?$_GET['id']:"";
$sentencia=$conexion->prepare("SELECT * FROM trabajador WHERE id=:id ");
$sentencia->bindParam(":id",$idTrabajador);
$sentencia->execute();
$datostrabajador=$sentencia->fetch(PDO::FETCH_LAZY);
   //Grupo Familiar Consulta para obtener todos los miembros del grupo familiar asociados al trabajador
$sentencia = $conexion->prepare("SELECT * FROM grupo_familiar WHERE trabajador_id = :trabajador_id");
$sentencia->bindParam(":trabajador_id", $idTrabajador);
$sentencia->execute();
$datosGFamiliar = $sentencia->fetchAll(PDO::FETCH_ASSOC);

//Historia Familiar (en la actualidad)
$sentencia = $conexion->prepare("SELECT * FROM historia_familiar WHERE trabajador_id = :trabajador_id");
$sentencia->bindParam(":trabajador_id", $idTrabajador);
$sentencia->execute();
$historiaFamiliar = $sentencia->fetch(PDO::FETCH_ASSOC);
// Si no existe historia familiar, inicializamos el array con un valor vacío
$historiaFamiliar = $historiaFamiliar ? $historiaFamiliar : ['historia' => ''];

//Ayuda familiar economicamente
$sentencia = $conexion->prepare("SELECT * FROM apoyo_economico WHERE trabajador_id = :trabajador_id");
$sentencia->bindParam(":trabajador_id", $idTrabajador);
$sentencia->execute();
$apoyoEconomicoT = $sentencia->fetchAll(PDO::FETCH_ASSOC);

//Emprendimiento trabajador 
$sentencia = $conexion->prepare("SELECT * FROM emprendimiento WHERE trabajador_id = :trabajador_id");
$sentencia->bindParam(":trabajador_id", $idTrabajador);
$sentencia->execute();
$emprendimientoT = $sentencia->fetch(PDO::FETCH_ASSOC);
// Si no existe emprendimiento del trabajador, inicializamos el array con un valor vacío
if (!$emprendimientoT) {
    $emprendimientoT = ['descripcion' => ''];
}

//Mascota del trabajador 
$sentencia = $conexion->prepare("SELECT * FROM mascotas WHERE trabajador_id = :trabajador_id");
$sentencia->bindParam(":trabajador_id", $idTrabajador);
$sentencia->execute();
$mascotasT = $sentencia->fetchAll(PDO::FETCH_ASSOC);
}

   //Ingresos
   $sentencia = $conexion->prepare("SELECT * FROM ingresos WHERE trabajador_id = :trabajador_id");
   $sentencia->bindParam(":trabajador_id", $idTrabajador);
   $sentencia->execute();
   $ingresos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

      //Egresos
      $sentencia = $conexion->prepare("SELECT * FROM egresos WHERE trabajador_id = :trabajador_id");
      $sentencia->bindParam(":trabajador_id", $idTrabajador);
      $sentencia->execute();
      $egresos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    //Habitalidad
    $idTrabajador=(isset($_GET['id']))?$_GET['id']:"";
    $sentencia=$conexion->prepare("SELECT * FROM condiciones_habitabilidad WHERE trabajador_id=:trabajador_id ");
    $sentencia->bindParam(":trabajador_id",$idTrabajador);
    $sentencia->execute();
    $habitalidad=$sentencia->fetch(PDO::FETCH_LAZY);
    //Mapa conceptual
$sentencia = $conexion->prepare("SELECT * FROM mapa_conceptual WHERE trabajador_id = :trabajador_id");
$sentencia->bindParam(":trabajador_id", $idTrabajador);
$sentencia->execute();
$mapaConceptual= $sentencia->fetch(PDO::FETCH_ASSOC);
$mapaConceptual = $mapaConceptual ? $mapaConceptual : ['mapa_conceptual' => ''];
    //Otros
    $sentencia = $conexion->prepare("SELECT * FROM otros WHERE trabajador_id = :trabajador_id");
    $sentencia->bindParam(":trabajador_id", $idTrabajador);
    $sentencia->execute();
    $otros= $sentencia->fetch(PDO::FETCH_ASSOC);
    $otros = $otros ? $otros : ['descripcion' => ''];
    //beneficios valorados
    $sentencia = $conexion->prepare("SELECT * FROM beneficios_valorados WHERE trabajador_id = :trabajador_id");
    $sentencia->bindParam(":trabajador_id", $idTrabajador);
    $sentencia->execute();
    $beneficioV= $sentencia->fetch(PDO::FETCH_ASSOC);
    $beneficioV = $beneficioV ? $beneficioV : ['beneficio' => ''];
     //beneficios necesarios
     $sentencia = $conexion->prepare("SELECT * FROM beneficios_necesarios WHERE trabajador_id = :trabajador_id");
     $sentencia->bindParam(":trabajador_id", $idTrabajador);
     $sentencia->execute();
     $beneficioN= $sentencia->fetch(PDO::FETCH_ASSOC);
     $beneficioN = $beneficioN ? $beneficioN : ['beneficio' => ''];

          //declaracion de salud
          $sentencia = $conexion->prepare("SELECT * FROM declaracion_salud WHERE trabajador_id = :trabajador_id");
          $sentencia->bindParam(":trabajador_id", $idTrabajador);
          $sentencia->execute();
          $declaracionSalud= $sentencia->fetch(PDO::FETCH_ASSOC);

// Detalles Adicionales de Salud de Usted o su Grupo Familiar
$sentencia = $conexion->prepare("SELECT * FROM personas_enfermas WHERE trabajador_id = :trabajador_id");
$sentencia->bindParam(":trabajador_id", $idTrabajador);
$sentencia->execute();
$infoPersonaE = $sentencia->fetchAll(PDO::FETCH_ASSOC);
          

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="reportes.css">

    <title>Document</title>
</head>

<style>
    * {
    box-sizing: border-box; /* Asegura que el padding y el borde se incluyan en el ancho y alto de los elementos */
}

body {
    margin: 0;  /* Eliminar margen del body */
    padding: 0; /* Eliminar padding del body */
}
</style>
<body >
<h2>FICHA SOCIAL FAMILIAR - TRABAJADORES FASTPACK</h2>

            <h4>IDENTIFICACIÓN DEL TRABAJADOR</h4>
    <table style="border-collapse: collapse; width: 100%;">
    <tr>
        <th style="border: 1px solid black;">ID:</th> 
        <td style="border: 1px solid black;"><?php echo htmlspecialchars($datostrabajador['id']); ?></td>
    </tr>
    <tr>
        <th style="border: 1px solid black;">Rut:</th> 
        <td style="border: 1px solid black;"><?php echo htmlspecialchars($datostrabajador['rut']); ?></td>
    </tr>
    <tr>
        <th style="border: 1px solid black;">Nombre y Apellido:</th> 
        <td style="border: 1px solid black;"><?php echo htmlspecialchars($datostrabajador['nombre_apellido']); ?></td>
    </tr>
    <tr>
        <th style="border: 1px solid black;">Fecha de Nacimiento:</th> 
        <td style="border: 1px solid black;"><?php echo htmlspecialchars($datostrabajador['fecha_nacimiento']); ?></td>
    </tr>
    <tr>
        <th style="border: 1px solid black;">Nacionalidad:</th> 
        <td style="border: 1px solid black;"><?php echo htmlspecialchars($datostrabajador['nacionalidad']); ?></td>
    </tr>
    <tr>
        <th style="border: 1px solid black;">Sexo:</th> 
        <td style="border: 1px solid black;"><?php echo ($datostrabajador['sexo'] == 'M') ? 'Masculino' : (($datostrabajador['sexo'] == 'F') ? 'Femenino' : ''); ?></td>
    </tr>
    <tr>
        <th style="border: 1px solid black;">Domicilio: </th> 
        <td style="border: 1px solid black;"><?php echo htmlspecialchars($datostrabajador['domicilio']); ?></td>
    </tr>
    <tr>
        <th style="border: 1px solid black;">Teléfono:</th> 
        <td style="border: 1px solid black;"><?php echo htmlspecialchars($datostrabajador['telefono']); ?></td>
    </tr>
    <tr>
        <th style="border: 1px solid black;">Celular:</th> 
        <td style="border: 1px solid black;"><?php echo htmlspecialchars($datostrabajador['celular']); ?></td>
    </tr>
    <tr>
        <th style="border: 1px solid black;">Correo Electrónico:</th> 
        <td style="border: 1px solid black;"><?php echo htmlspecialchars($datostrabajador['correo_electronico']); ?></td>
    </tr>
    <tr>
        <th style="border: 1px solid black;">Estado Civil:</th> 
        <td style="border: 1px solid black;"><?php echo htmlspecialchars($datostrabajador['estado_civil']); ?></td>
    </tr>
    <tr>
        <th style="border: 1px solid black;">Previsión de Salud:</th> 
        <td style="border: 1px solid black;"><?php echo htmlspecialchars($datostrabajador['prevision_salud']); ?></td>
    </tr>
</table>
<!-- 2. Grupo Familiar -->
        <h4>GRUPO FAMILIAR</h4>
            <table id="grupo_familiar" border="1" style="<?php echo empty($datosGFamiliar) ? 'display:none;' : ''; ?> border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid black; padding: 8px; max-width: 150px; word-wrap: break-word;">Nombre y Apellido</th>
            <th style="border: 1px solid black; padding: 8px; max-width: 100px; word-wrap: break-word;">Parentesco</th>
            <th style="border: 1px solid black; padding: 8px; max-width: 100px; word-wrap: break-word;">Fecha de Nacimiento</th>
            <th style="border: 1px solid black; padding: 8px; max-width: 50px; word-wrap: break-word;">Sexo M/F</th>
            <th style="border: 1px solid black; padding: 8px; max-width: 100px; word-wrap: break-word;">Estado Civil</th>
            <th style="border: 1px solid black; padding: 8px; max-width: 100px; word-wrap: break-word;">Nivel educacional</th>
            <th style="border: 1px solid black; padding: 8px; max-width: 100px; word-wrap: break-word;">Actividad</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($datosGFamiliar)): ?>
            <?php foreach ($datosGFamiliar as $familiar): ?>
                <tr>
                    <td style="border: 1px solid black; padding: 8px; max-width: 150px; word-wrap: break-word;">
                        <?php echo htmlspecialchars($familiar['nombre_apellido']); ?>
                    </td>
                    <td style="border: 1px solid black; padding: 8px; max-width: 100px; word-wrap: break-word;">
                        <?php echo htmlspecialchars($familiar['parentesco']); ?>
                    </td>
                    <td style="border: 1px solid black; padding: 8px; max-width: 100px; word-wrap: break-word;">
                        <?php echo htmlspecialchars($familiar['fecha_nacimiento']); ?>
                    </td>
                    <td style="border: 1px solid black; padding: 8px; max-width: 50px; word-wrap: break-word;">
                        <?php echo htmlspecialchars($familiar['sexo']); ?>
                    </td>
                    <td style="border: 1px solid black; padding: 8px; max-width: 100px; word-wrap: break-word;">
                        <?php echo htmlspecialchars($familiar['estado_civil']); ?>
                    </td>
                    <td style="border: 1px solid black; padding: 8px; max-width: 100px; word-wrap: break-word;">
                        <?php echo htmlspecialchars($familiar['nivel_educacional']); ?>
                    </td>
                    <td style="border: 1px solid black; padding: 8px; max-width: 100px; word-wrap: break-word;">
                        <?php echo htmlspecialchars($familiar['actividad']); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

            <!-- Mensaje de "No hay miembros familiares registrados" -->
            <p id="no-miembros-msg" style="display: <?php echo empty($datosGFamiliar) ? 'block' : 'none'; ?>;">No hay
                miembros familiares registrados.</p>

        <!-- 4. Historia Familiar -->
            <h4>HISTORIA FAMILIAR (EN LA ACTUALIDAD)</h4>
            <textarea name="historia_familiar" rows="5"
                cols="50"><?php echo htmlspecialchars($historiaFamiliar['historia']); ?></textarea>

        <!-- 5. ¿Apoya a algún familiar económicamente? -->
            <h4>¿APOYA A ALGÚN FAMILIAR ECONÓMICAMENTE?</h4>
            <label>Si <input type="radio" name="apoyo_economico" value="si" onclick="handleRadioChange(this)" <?php echo
                    !empty($apoyoEconomicoT) ? 'checked' : '' ; ?>></label>
            <label>No <input type="radio" name="apoyo_economico" value="no" onclick="handleRadioChange(this)" <?php echo
                    empty($apoyoEconomicoT) ? 'checked' : '' ; ?>></label><br>
            <div id="contenedor_apoyo_economico"
                style="display: <?php echo !empty($apoyoEconomicoT) ? 'block' : 'none'; ?>;">

                <table id="apoyo_economico" border="1" style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid black; padding: 8px;">¿A quién?</th>
            <th style="border: 1px solid black; padding: 8px;">Motivo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($apoyoEconomicoT as $apoyo): ?>
        <tr>
            <td style="border: 1px solid black; padding: 8px;">
            <?php echo htmlspecialchars($apoyo['a_quien']); ?>
            </td>
            <td style="border: 1px solid black; padding: 8px;">
            <?php echo htmlspecialchars($apoyo['motivo']); ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

            </div>



        <!-- 6. ¿Tiene algún emprendimiento? -->
            <h4>¿TIENE ALGÚN EMPRENDIMIENTO?</h4>
            <label>Si <input type="radio" name="emprendimiento" value="si" onclick="handleRadioChangeE(this)" <?php echo
                    !empty($emprendimientoT['descripcion']) ? 'checked' : '' ; ?>></label>
            <label>No <input type="radio" name="emprendimiento" value="no" onclick="handleRadioChangeE(this)" <?php echo
                    empty($emprendimientoT['descripcion']) ? 'checked' : '' ; ?>></label><br>
            <div id="contenedor_emprendimiento"
                style="display: <?php echo !empty($emprendimientoT['descripcion']) ? 'block' : 'none'; ?>;">
                <table style="border-collapse: collapse; width: 100%;">
    <tr>
        <td style="border: 1px solid black; padding: 8px;">
            <label for="descripcion_emprendimiento">¿De qué se trata?:</label>
        </td>
        <td style="border: 1px solid black; padding: 8px;">
        <?php echo htmlspecialchars($emprendimientoT['descripcion']); ?>
        </td>
    </tr>
</table>

                
            </div>


        <!-- 7. ¿Tiene Mascotas? -->
            <h4>7. ¿Tiene Mascotas?</h4>
            <label>Si <input type="radio" name="mascota" value="si" onclick="handleRadioChangeM(this)" <?php echo
                    !empty($mascotasT) ? 'checked' : '' ; ?>></label>
            <label>No <input type="radio" name="mascota" value="no" onclick="handleRadioChangeM(this)" <?php echo
                    empty($mascotasT) ? 'checked' : '' ; ?>></label><br>
            <div id="contenedor_mascotas" style="display: <?php echo !empty($mascotasT) ? 'block' : 'none'; ?>;">
            <table id="mascotas" style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid black; padding: 8px;">¿Qué tipo de mascota?:</th>
            <th style="border: 1px solid black; padding: 8px;">¿Cuántas?:</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mascotasT as $mascota): ?>
        <tr>
            <td style="border: 1px solid black; padding: 8px;">
            <?php echo htmlspecialchars($mascota['tipo_mascota']); ?>
            </td>
            <td style="border: 1px solid black; padding: 8px;">
            <?php echo htmlspecialchars($mascota['cantidad']); ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

            </div>
        <!-- 7. Situacion economica -->
            <h4>SITUACIÓN ECONÓMICA</h4>
            <!-- 8.1 Directa -->
    <h5>DIRECTA</h5>

    <!-- La tabla se oculta si no hay datos -->
    <table id="ingresos_familiares" border="1" style="<?php echo empty($ingresos) ? 'display:none;' : ''; ?> border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid black;">Nombre</th>
            <th style="border: 1px solid black;">Monto</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($ingresos)): ?>
            <?php foreach ($ingresos as $ingreso): ?>
                <tr>
                    <td style="border: 1px solid black;">
                    <?php echo htmlspecialchars($ingreso['nombre_persona']); ?>
                    </td>
                    <td style="border: 1px solid black;">
                    <?php echo htmlspecialchars($ingreso['monto']); ?> 
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
    <!-- Mensaje de "No hay ingresos registrados" -->
    <p id="no-ingresos-msg" style="display: <?php echo empty($ingresos) ? 'block' : 'none'; ?>;">No hay ingresos registrados.</p>

            <!-- 8.2 Egresos importantes -->
    <h5>EGRESOS IMPORTANTES</h5>

    <!-- La tabla se oculta si no hay datos -->
    <table id="egresos_importantes" border="1" style="<?php echo empty($egresos) ? 'display:none;' : ''; ?> border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th style="border: 1px solid black; padding: 8px;">Descripción</th>
            <th style="border: 1px solid black; padding: 8px;">Monto</th>
            <th style="border: 1px solid black; padding: 8px;">Observaciones</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($egresos)): ?>
            <?php foreach ($egresos as $egreso): ?>
                <tr>
                    <td style="border: 1px solid black; padding: 8px;">
                    <?php echo htmlspecialchars($egreso['descripcion']); ?>
                    </td>
                    <td style="border: 1px solid black; padding: 8px;">
                    <?php echo htmlspecialchars($egreso['monto']); ?>
                    </td>
                    <td style="border: 1px solid black; padding: 8px;">
                    <?php echo htmlspecialchars($egreso['observaciones']); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>


    <!-- Mensaje de "No hay egresos registrados" -->
    <p id="no-egresos-msg" style="display: <?php echo empty($egresos) ? 'block' : 'none'; ?>;">No hay egresos registrados.</p>


    <label>Total Egresos: <input type="number" id="total_egresos" readonly></label>


        <!-- 9. Condiciones de Habitabilidad -->
        <h4>9. Condiciones de Habitabilidad</h4>
<table style="border-collapse: collapse; width: 100%; border: 1px solid black;">
    <tr>
        <td style="border: 1px solid black;">
            <label for="tipo_vivienda">Tipo de Vivienda:</label>
        </td>
        <td style="border: 1px solid black;">
        <?php echo (isset($habitalidad['tipo_vivienda']) &&
                    $habitalidad['tipo_vivienda']=='propia' ) ? 'selected' : '' ; ?>
        </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">
            <label for="material_vivienda">Material de la Vivienda:</label>
        </td>
        <td style="border: 1px solid black;">
        <?php echo (isset($habitalidad['material_vivienda']) &&
                    $habitalidad['material_vivienda']=='seleccionar' ) ? 'selected' : '' ; ?>
        </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">
            <label for="numero_habitaciones">Número de Habitaciones:</label>
        </td>
        <td style="border: 1px solid black;">
        <?php echo isset($habitalidad['num_habitaciones']) ? $habitalidad['num_habitaciones'] : 0; ?>
        </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">
            <label for="numero_banos">Número de Baños:</label>
        </td>
        <td style="border: 1px solid black;">
            <?php echo isset($habitalidad['num_banos']) ? htmlspecialchars($habitalidad['num_banos']) : 0; ?>
        </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">
            <label for="cocina">Cocina:</label>
        </td>
        <td style="border: 1px solid black;">
        <?php echo isset($habitalidad['num_cocina']) ? htmlspecialchars($habitalidad['num_cocina']) : 0; ?>
        </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">
            <label for="logia">Logia:</label>
        </td>
        <td style="border: 1px solid black;">
        <?php echo isset($habitalidad['num_logia']) ? htmlspecialchars($habitalidad['num_logia']) : 0; ?>
        </td>
    </tr>
    <tr>
        <td style="border: 1px solid black;">
            <label for="condiciones_habitabilidad">Condiciones de Habitabilidad:</label>
        </td>
        <td style="border: 1px solid black;">
        <?php echo (isset($habitalidad['condiciones_habitabilidad']) &&
                    $habitalidad['condiciones_habitabilidad']=='normal' ) ? 'selected' : '' ; ?>
        </td>
    </tr>
</table>


        <!-- 10. Mapa conceptual -->

            <h4>10. Mapa conceptual</h4>
            <p>(Herramienta para la comprensión del entorno en que se desarrolla la vida del trabajador y su familia)
            </p>
            <!-- Este espacio está reservado para incluir un mapa conceptual o un campo donde el trabajador pueda describir su entorno -->
            <textarea name="mapa_conceptual" rows="10" cols="80"
                placeholder="Describe aquí el entorno en el que se desarrolla la vida del trabajador y su familia..."><?php echo htmlspecialchars($mapaConceptual['mapa_conceptual']); ?></textarea>
    
        <!-- 11. Otros -->

            <h4>11. Otros</h4>
            <textarea name="otros" rows="10" cols="80"
                placeholder="Agregar cualquier otra información relevante..."><?php echo htmlspecialchars($otros['descripcion']); ?></textarea>

        <!-- 12. ¿Qué beneficios valora de parte de la empresa? -->
        
            <h4>12. ¿Qué beneficios valora de parte de la empresa?</h4>
            <textarea name="beneficios_valora" rows="5" cols="80"
                placeholder="Escribe aquí los beneficios que valoras de la empresa..."><?php echo htmlspecialchars($beneficioV['beneficio']); ?></textarea>

        <!-- 13. ¿Qué beneficios no tenemos y considera son necesarios? -->
        
            <h4>13. ¿Qué beneficios no tenemos y considera son necesarios?</h4>
            <textarea name="beneficios_necesarios" rows="5" cols="80"
                placeholder="Escribe aquí los beneficios que consideras necesarios..."><?php echo htmlspecialchars($beneficioN['beneficio']); ?></textarea>
        
        <!-- 14. Declaración de salud -->
        <fieldset>
            <legend>14. Declaración de salud</legend>
            <p>Usted o alguien de su grupo familiar, padece, ha padecido, se encuentra en proceso de diagnóstico o
                presenta cualquier sintomatología de:</p>

            <table>
                <tr>
                    <th>Condición</th>
                    <th>Sí</th>
                    <th>No</th>
                </tr>
                <tr>
                    <td>Cáncer, tumores, pólipos, nódulos, enfermedad de los ganglios linfáticos, leucemia, linfomas,
                        aplasia medular.</td>
                    <td><input type="radio" name="salud_cancer" value="si" <?php echo
                            (isset($declaracionSalud['salud_cancer']) && $declaracionSalud['salud_cancer']==='si' )
                            ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_cancer" value="no" <?php echo
                            (isset($declaracionSalud['salud_cancer']) && $declaracionSalud['salud_cancer']==='no' )
                            ? 'checked' : '' ; ?>></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Epilepsia, parkinson, neuropatías, esclerosis múltiple, parálisis, accidentes cerebrovasculares,
                        derrame cerebral, aneurisma, infarto cerebral, encefalitis u otra enfermedad del sistema
                        nervioso central.</td>
                    <td><input type="radio" name="salud_sistema_nervioso" value="si" <?php echo
                            (isset($declaracionSalud['salud_sistema_nervioso']) &&
                            $declaracionSalud['salud_sistema_nervioso']==='si' ) ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_sistema_nervioso" value="no" <?php echo
                            (isset($declaracionSalud['salud_sistema_nervioso']) &&
                            $declaracionSalud['salud_sistema_nervioso']==='no' ) ? 'checked' : '' ; ?>></td>

                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Depresión, esquizofrenia, trastornos del ánimo o de la personalidad, psicosis, crisis de pánico,
                        bulimia, anorexia u otra enfermedad de salud mental.</td>
                    <td><input type="radio" name="salud_salud_mental" value="si" <?php echo
                            (isset($declaracionSalud['salud_salud_mental']) &&
                            $declaracionSalud['salud_salud_mental']==='si' ) ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_salud_mental" value="no" <?php echo
                            (isset($declaracionSalud['salud_salud_mental']) &&
                            $declaracionSalud['salud_salud_mental']==='no' ) ? 'checked' : '' ; ?>></td>

                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Miopía, astigmatismo, hipermetropía, glaucoma, cataratas, estrabismo, queratocono, ceguera, o
                        cualquier enfermedad a los ojos.</td>
                    <td><input type="radio" name="salud_ojo" value="si" <?php echo
                            (isset($declaracionSalud['salud_ojo']) && $declaracionSalud['salud_ojo']==='si' )
                            ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_ojo" value="no" <?php echo
                            (isset($declaracionSalud['salud_ojo']) && $declaracionSalud['salud_ojo']==='no' )
                            ? 'checked' : '' ; ?>></td>

                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Desviación tabique nasal, obstrucción nasal, sordera, hipoacusia, o cualquier otra enfermedad
                        que afecta a la nariz o los oídos.</td>
                    <td><input type="radio" name="salud_nariz_oidos" value="si" <?php echo
                            (isset($declaracionSalud['salud_nariz_oidos']) &&
                            $declaracionSalud['salud_nariz_oidos']==='si' ) ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_nariz_oidos" value="no" <?php echo
                            (isset($declaracionSalud['salud_nariz_oidos']) &&
                            $declaracionSalud['salud_nariz_oidos']==='no' ) ? 'checked' : '' ; ?>></td>

                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Enfermedad pulmonar obstructiva crónica (EPOC), enfisema pulmonar, fibrosis quística, asma,
                        neumotórax, pleuresía, fibrosis pulmonar y cualquier otra enfermedad que afecte al sistema
                        respiratorio.</td>
                    <td><input type="radio" name="salud_respiratorio" value="si" <?php echo
                            (isset($declaracionSalud['salud_respiratorio']) &&
                            $declaracionSalud['salud_respiratorio']==='si' ) ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_respiratorio" value="no" <?php echo
                            (isset($declaracionSalud['salud_respiratorio']) &&
                            $declaracionSalud['salud_respiratorio']==='no' ) ? 'checked' : '' ; ?>></td>

                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Hipertensión arterial, angina, infarto al miocardio, by-pass o angioplastia, soplos, enfermedad
                        reumática, arritmias, portador de marcapaso, insuficiencia cardíaca, enfermedad de las válvulas
                        del corazón o cualquier otra enfermedad del corazón.</td>
                    <td><input type="radio" name="salud_corazon" value="si" <?php echo
                            (isset($declaracionSalud['salud_corazon']) && $declaracionSalud['salud_corazon']==='si' )
                            ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_corazon" value="no" <?php echo
                            (isset($declaracionSalud['salud_corazon']) && $declaracionSalud['salud_corazon']==='no' )
                            ? 'checked' : '' ; ?>></td>

                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Aneurisma aórtico, tromboflebitis, várices, tratamiento anticoagulante, trombosis venosa
                        profunda, claudicación intermitente, enfermedades del sistema vascular, alteraciones de
                        coagulación, trombofilia, hemofilia y/o enfermedades del bazo y médula ósea.</td>
                    <td><input type="radio" name="salud_vascular" value="si" <?php echo
                            (isset($declaracionSalud['salud_vascular']) && $declaracionSalud['salud_vascular']==='si' )
                            ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_vascular" value="no" <?php echo
                            (isset($declaracionSalud['salud_vascular']) && $declaracionSalud['salud_vascular']==='no' )
                            ? 'checked' : '' ; ?>></td>

                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Síndrome metabólico, colesterol alto, triglicéridos altos, dislipidemia, hiperuricemia o gota,
                        resistencia a la insulina, resistencia a la glucosa, diabetes mellitus, sobrepeso, obesidad, u
                        otras alteraciones metabólicas.</td>
                    <td><input type="radio" name="salud_metabolico" value="si" <?php echo
                            (isset($declaracionSalud['salud_metabolico']) &&
                            $declaracionSalud['salud_metabolico']==='si' ) ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_metabolico" value="no" <?php echo
                            (isset($declaracionSalud['salud_metabolico']) &&
                            $declaracionSalud['salud_metabolico']==='no' ) ? 'checked' : '' ; ?>></td>

                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Cirrosis, várices esofágicas, insuficiencia hepática, pancreatitis, enfermedad a la vesícula
                        biliar, esófago de Barret, colitis ulcerosa, enfermedad de Crohn, úlceras digestivas,
                        hemorragias digestivas, sangramiento anal, hernia hiatal, hernias abdominales e inguinales,
                        diverticulitis, pólipos de colon u otras enfermedades que afecten al sistema digestivo.</td>
                    <td><input type="radio" name="salud_digestivo" value="si" <?php echo
                            (isset($declaracionSalud['salud_digestivo']) && $declaracionSalud['salud_digestivo']==='si'
                            ) ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_digestivo" value="no" <?php echo
                            (isset($declaracionSalud['salud_digestivo']) && $declaracionSalud['salud_digestivo']==='no'
                            ) ? 'checked' : '' ; ?>></td>

                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Hepatitis B, hepatitis C, síndrome de inmunodeficiencia adquirida (SIDA) (portador o enfermo).
                    </td>
                    <td><input type="radio" name="salud_hepatitis_sida" value="si" <?php echo
                            (isset($declaracionSalud['salud_hepatitis_sida']) &&
                            $declaracionSalud['salud_hepatitis_sida']==='si' ) ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_hepatitis_sida" value="no" <?php echo
                            (isset($declaracionSalud['salud_hepatitis_sida']) &&
                            $declaracionSalud['salud_hepatitis_sida']==='no' ) ? 'checked' : '' ; ?>></td>

                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Cálculos renales, nefritis, pielonefritis, riñones poliquísticos, insuficiencia renal,
                        malformación de riñones o de las vías urinarias, enfermedades a la vejiga, testículos o
                        próstata.</td>
                    <td><input type="radio" name="salud_renal" value="si" <?php echo
                            (isset($declaracionSalud['salud_renal']) && $declaracionSalud['salud_renal']==='si' )
                            ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_renal" value="no" <?php echo
                            (isset($declaracionSalud['salud_renal']) && $declaracionSalud['salud_renal']==='no' )
                            ? 'checked' : '' ; ?>></td>

                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Enfermedad de las mamas, mioma uterino, quistes ováricos, endometriosis, PAP alterados u otras
                        enfermedades que afecten el aparato reproductor femenino.</td>
                    <td><input type="radio" name="salud_reproductor_femenino" value="si" <?php echo
                            (isset($declaracionSalud['salud_reproductor_femenino']) &&
                            $declaracionSalud['salud_reproductor_femenino']==='si' ) ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_reproductor_femenino" value="no" <?php echo
                            (isset($declaracionSalud['salud_reproductor_femenino']) &&
                            $declaracionSalud['salud_reproductor_femenino']==='no' ) ? 'checked' : '' ; ?>></td>

                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Lupus eritromatoso, artritis reumatoidea, cirrosis hepática autoinmune, miastenia gravis,
                        tiroiditis de Hashimoto, esclerosis múltiple, esclerosis lateral amiotrófica,
                        poliorradiculopatía desmielinizante inflamatoria crónica, síndrome Guillain-Barré, fibromialgia,
                        eritema nodoso u otras enfermedades autoinmunes.</td>
                    <td><input type="radio" name="salud_autoinmune" value="si" <?php echo
                            (isset($declaracionSalud['salud_autoinmune']) &&
                            $declaracionSalud['salud_autoinmune']==='si' ) ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_autoinmune" value="no" <?php echo
                            (isset($declaracionSalud['salud_autoinmune']) &&
                            $declaracionSalud['salud_autoinmune']==='no' ) ? 'checked' : '' ; ?>></td>

                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Hipotiroidismo, hipertiroidismo, nódulos a la tiroides o bocio multinodular u otras patologías
                        de tiroides.</td>
                    <td><input type="radio" name="salud_tiroides" value="si" <?php echo
                            (isset($declaracionSalud['salud_tiroides']) && $declaracionSalud['salud_tiroides']==='si' )
                            ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_tiroides" value="no" <?php echo
                            (isset($declaracionSalud['salud_tiroides']) && $declaracionSalud['salud_tiroides']==='no' )
                            ? 'checked' : '' ; ?>></td>

                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Artrosis, osteoporosis, hernias de columna, escoliosis, espondilosis o discopatía, meniscopatía
                        o lesiones a la rodilla.</td>
                    <td><input type="radio" name="salud_esqueletico" value="si" <?php echo
                            (isset($declaracionSalud['salud_esqueletico']) &&
                            $declaracionSalud['salud_esqueletico']==='si' ) ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_esqueletico" value="no" <?php echo
                            (isset($declaracionSalud['salud_esqueletico']) &&
                            $declaracionSalud['salud_esqueletico']==='no' ) ? 'checked' : '' ; ?>></td>

                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Cardiopatías congénitas, fisura palatina o labio leporino, displasia de cadera, síndrome de
                        Down, o cualquier otra malformación o patología congénita.</td>
                    <td><input type="radio" name="salud_congenito" value="si" <?php echo
                            (isset($declaracionSalud['salud_congenito']) && $declaracionSalud['salud_congenito']==='si'
                            ) ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_congenito" value="no" <?php echo
                            (isset($declaracionSalud['salud_congenito']) && $declaracionSalud['salud_congenito']==='no'
                            ) ? 'checked' : '' ; ?>></td>

                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Embarazo actual.</td>
                    <td><input type="radio" name="salud_embarazo" value="si" <?php echo
                            (isset($declaracionSalud['salud_embarazo']) && $declaracionSalud['salud_embarazo']==='si' )
                            ? 'checked' : '' ; ?>></td>
                    <td><input type="radio" name="salud_embarazo" value="no" <?php echo
                            (isset($declaracionSalud['salud_embarazo']) && $declaracionSalud['salud_embarazo']==='no' )
                            ? 'checked' : '' ; ?>></td>

                </tr>
            </table>
        </fieldset>
        <!-- 15. Detalle de Enfermedades Adicionales -->
        <!-- 15. Detalle de Enfermedades Adicionales -->
            <legend>15. Detalles Adicionales de Salud de Usted o su Grupo Familiar</legend>
            <p>Si usted ha respondido afirmativamente en el cuestionario anterior, o si usted o alguien de su grupo
                familiar, padece alguna otra enfermedad, patología o condición de salud que no haya sido antes
                detallada, agradecemos completar el detalle señalado a continuación.</p>

                <table id="enfermedades_adicionales" border="1" style="border-collapse: collapse; <?php echo empty($infoPersonaE) ? 'display:none;' : ''; ?>">
    <thead>
        <tr>
            <th style="border: 1px solid black;">Nombre Persona Afectada</th>
            <th style="border: 1px solid black;">Enfermedad</th>
            <th style="border: 1px solid black;">Fecha Diagnóstico</th>
            <th style="border: 1px solid black;">Estado Actual (Alta, Tratamiento, Seguimiento)</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($infoPersonaE)): ?>
            <?php foreach ($infoPersonaE as $inforPersona): ?>
                <tr>
                    <td style="border: 1px solid black;"><?php echo htmlspecialchars($inforPersona['nombre_persona']); ?></td>
                    <td style="border: 1px solid black;"><?php echo htmlspecialchars($inforPersona['enfermedad']); ?></td>
                    <td style="border: 1px solid black;"><?php echo htmlspecialchars($inforPersona['fecha_diagnostico']); ?></td>
                    <td style="border: 1px solid black;"><?php echo htmlspecialchars($inforPersona['estado_actual']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>


            <!-- Mensaje de "No hay enfermedades registradas" -->
            <p id="no-enfermedades-msg" style="display: <?php echo empty($infoPersonaE) ? 'block' : 'none'; ?>;">No
                hay
                enfermedades registradas.</p>


        </body>
</html>
<?php
$html=ob_get_clean();

require_once('./libreria/dompdf/autoload.inc.php');

use Dompdf\Dompdf;
$dompdf = new Dompdf();

$options = $dompdf->getOptions();
$options->set(array('isRemoteEnabled' => true));
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('letter');
//$dompdf->setPaper('A4', 'landscape');

$dompdf->render();

$dompdf->stream("archivo_.pdf",array("Attachment" => false));



?>