<?php 
include("bd.php");

session_start();

if (isset($_SESSION["usuario"])) {
    //echo "Usuario Activo: ".$_SESSION["usuario"];
}else {
    header("location:login.php");
}

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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/styleFormulario.css">
    <!--excel-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>


    <title>Ficha Social Familiar – Trabajadores Fastpack</title>

</head>

<body onload="disableInputs()"> <!-- Deshabilitar inputs al cargar la página -->
    <header>
        <div class="btn-formulario">
            <a class="btn-icon-atras" href="index.php"><i class="bi bi-arrow-left fs-2"></i></a>
            <h1>Ficha Social Familiar<span class="span-titulo"> – Trabajadores Fastpack</span></h1>
            <button id="enableButton" class="btn-editar" type="button" onclick="enableInputs()"><i
                    class="bi bi-journal-text fs-2"></i></button>
        </div>
    </header>

    <main>
        <form id="form" method="POST" action="code.php">
            <!-- 1. Identificación del Trabajador -->
            <fieldset>
                <legend>1. Identificación del Trabajador</legend>
                <div class="table-identificacion">
                    <div class="row">
                        <div class="col-name">Rut:</div>
                        <div class="col-input">
                            <input type="hidden" name="id"
                                value="<?php echo htmlspecialchars($datostrabajador['id']); ?>" readonly>
                            
                                <input type="text" name="rut"
                                value="<?php echo htmlspecialchars($datostrabajador['rut']); ?>" readonly>
                            </div>
                        <div class="col-name">Nombre y Apellido:</div>
                        <div class="col-input"><input type="text" name="nombre_apellido"
                                value="<?php echo htmlspecialchars($datostrabajador['nombre_apellido']); ?>" readonly></div>
                    </div>
                    <div class="row">
                        <div class="col-name">Fecha de Nacimiento:</div>
                        <div class="col-input"><input type="date" name="fecha_nacimiento"
                                value="<?php echo htmlspecialchars($datostrabajador['fecha_nacimiento']); ?>" readonly></div>
                        <div class="col-name">Nacionalidad:</div>
                        <div class="col-input"><input type="text" name="nacionalidad"
                                value="<?php echo htmlspecialchars($datostrabajador['nacionalidad']); ?>" readonly></div>
                    </div>
                    <div class="row">
                        <div class="col-name">Domicilio:</div>
                        <div class="col-input"><input type="text" name="domicilio"
                                value="<?php echo htmlspecialchars($datostrabajador['domicilio']); ?>"></div>
                        <div class="col-name">Teléfono:</div>
                        <div class="col-input"><input type="tel" name="telefono"
                                value="<?php echo htmlspecialchars($datostrabajador['telefono']); ?>"></div>
                    </div>
                    <div class="row">
                        <div class="col-name">Celular:</div>
                        <div class="col-input"><input type="tel" name="celular"
                                value="<?php echo htmlspecialchars($datostrabajador['celular']); ?>"></div>
                        <div class="col-name">Sexo:</div>
                        <div class="col-input"><input type="text" name="sexo"
                                value="<?php echo ($datostrabajador['sexo'] == 'M') ? 'Masculino' : (($datostrabajador['sexo'] == 'F') ? 'Femenino' : ''); ?>" readonly></div>
                    </div>
                    <div class="row">
                        <div class="col-name">Correo Electrónico:</div>
                        <div class="col-input"><input type="email" name="correo"
                                value="<?php echo htmlspecialchars($datostrabajador['correo_electronico']); ?>"></div>
                        <div class="col-name">Estado Civil:</div>
                        <div class="col-input"><input type="text" name="estado_civil"
                                value="<?php echo htmlspecialchars($datostrabajador['estado_civil']); ?>"></div>
                    </div>
                    <div class="row">
                        <div class="col-name">Previsión de Salud:</div>
                        <div class="col-input" colspan="3">
                            <select id="previsionSalud" name="prevision_salud">
                                <option value="" <?php echo (isset($datostrabajador['prevision_salud']) &&
                                    $datostrabajador['prevision_salud']=='' ) ? 'selected' : '' ; ?>>Seleccionar
                                </option>
                                <option value="fonasa" <?php echo (isset($datostrabajador['prevision_salud']) &&
                                    $datostrabajador['prevision_salud']=='fonasa' ) ? 'selected' : '' ; ?>>FONASA
                                </option>
                                <option value="isapre" <?php echo (isset($datostrabajador['prevision_salud']) &&
                                    $datostrabajador['prevision_salud']=='isapre' ) ? 'selected' : '' ; ?>>ISAPRE
                                </option>
                            </select>
                        </div>
                    </div>

                </div>
            </fieldset>

            <!-- 2. Grupo Familiar -->
            <fieldset>
                <legend>2. Grupo Familiar</legend>

                <!-- La tabla se oculta si no hay datos -->
                <div class="table-responsive">
                    <table id="grupo_familiar" border="1"
                        style="<?php echo empty($datosGFamiliar) ? 'display:none;' : ''; ?>">
                        <thead>
                            <tr>
                                <th>Nombre y Apellido</th>
                                <th>Parentesco</th>
                                <th>Fecha de Nacimiento</th>
                                <th>Sexo M/F</th>
                                <th>Estado Civil</th>
                                <th>Nivel educacional</th>
                                <th>Actividad</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($datosGFamiliar)): ?>
                            <?php foreach ($datosGFamiliar as $familiar): ?>
                            <tr>
                                <td><input type="text" name="nombre_apellido_familiar[]"
                                        value="<?php echo htmlspecialchars($familiar['nombre_apellido']); ?>"></td>
                                <td><input type="text" name="parentesco[]"
                                        value="<?php echo htmlspecialchars($familiar['parentesco']); ?>"></td>
                                <td><input type="date" name="fecha_nacimiento_familiar[]"
                                        value="<?php echo htmlspecialchars($familiar['fecha_nacimiento']); ?>"></td>
                                <td><input type="text" name="sexo_familiar[]"
                                        value="<?php echo htmlspecialchars($familiar['sexo']); ?>"></td>
                                <td><input type="text" name="estado_civil_familiar[]"
                                        value="<?php echo htmlspecialchars($familiar['estado_civil']); ?>"></td>
                                <td><input type="text" name="nivel_educacional[]"
                                        value="<?php echo htmlspecialchars($familiar['nivel_educacional']); ?>"></td>
                                <td><input type="text" name="actividad_familiar[]"
                                        value="<?php echo htmlspecialchars($familiar['actividad']); ?>"></td>
                                <td>
                                    <button type="button" onclick="ocultarFilaGP(this)"><span
                                            class="texto-eliminar">Eliminar</span><i
                                            class="bi bi-trash icon-responsive"></i></button>
                                    <!-- Mantener el input para el ID del familiar -->
                                    <input type="hidden" name="id_familiar[]"
                                        value="<?php echo htmlspecialchars($familiar['id']); ?>">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mensaje de "No hay miembros familiares registrados" -->
                <p id="no-miembros-msg" style="display: <?php echo empty($datosGFamiliar) ? 'block' : 'none'; ?>;">No
                    hay
                    miembros familiares registrados.</p>

                <button type="button" onclick="agregarFilaGF()"><span class="texto-oculto">Agregar Miembro
                        Familiar</span><i class="bi bi-plus-lg icon-responsive"></i></button>
            </fieldset>

            <!-- 4. Historia Familiar -->
            <fieldset>
                <legend>4. Historia Familiar (en la actualidad)</legend>
                <textarea name="historia_familiar" rows="3"
                    cols="50"><?php echo htmlspecialchars($historiaFamiliar['historia']); ?></textarea>
            </fieldset>

            <!-- 5. ¿Apoya a algún familiar económicamente? -->
            <fieldset>
                <legend>5. ¿Apoya a algún familiar económicamente?</legend>

                <label>Si <input type="radio" class="inputRadio" name="apoyo_economico" value="si"
                        onclick="handleRadioChange(this)" <?php echo !empty($apoyoEconomicoT) ? 'checked' : '' ;
                        ?>></label>
                <label>No <input type="radio" class="inputRadio" name="apoyo_economico" value="no"
                        onclick="handleRadioChange(this)" <?php echo empty($apoyoEconomicoT) ? 'checked' : '' ;
                        ?>></label><br>

                <div id="contenedor_apoyo_economico"
                    style="display: <?php echo !empty($apoyoEconomicoT) ? 'block' : 'none'; ?>;">

                    <div class="table-responsive">
                        <table id="apoyo_economico" border="1">
                            <thead>
                                <tr>
                                    <th>¿A quién?</th>
                                    <th>Motivo</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($apoyoEconomicoT as $apoyo): ?>
                                <tr>
                                    <td><input type="text" name="a_quien_apoya[]"
                                            value="<?php echo htmlspecialchars($apoyo['a_quien']); ?>"></td>
                                    <td><input type="text" name="motivo_apoyo[]"
                                            value="<?php echo htmlspecialchars($apoyo['motivo']); ?>"></td>
                                    <td>
                                        <button type="button" onclick="eliminarFilaAPF(this)"><span
                                                class="texto-eliminar">Eliminar</span><i
                                                class="bi bi-trash icon-responsive"></button>
                                        <!-- Campo oculto para guardar el ID del apoyo -->
                                        <input type="hidden" value="<?php echo htmlspecialchars($apoyo['id']); ?>">
                                        <input type="hidden" name="id_apoyoF[]"
                                            value="<?php echo htmlspecialchars($apoyo['id']); ?>">
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <button type="button" onclick="agregarFilaAPF('apoyo_economico')"><span class="texto-oculto">Agregar
                            Familiar Apoyado</span><i class="bi bi-plus-lg icon-responsive"></i></button>
                </div>
            </fieldset>



            <!-- 6. ¿Tiene algún emprendimiento? -->
            <fieldset>
                <legend>6. ¿Tiene algún emprendimiento?</legend>
                <label>Si <input class="inputRadio" type="radio" name="emprendimiento" value="si"
                        onclick="handleRadioChangeE(this)" <?php echo !empty($emprendimientoT['descripcion'])
                        ? 'checked' : '' ; ?>></label>
                <label>No <input type="radio" class="inputRadio" name="emprendimiento" value="no"
                        onclick="handleRadioChangeE(this)" <?php echo empty($emprendimientoT['descripcion']) ? 'checked'
                        : '' ; ?>></label><br>
                <div id="contenedor_emprendimiento"
                    style="display: <?php echo !empty($emprendimientoT['descripcion']) ? 'block' : 'none'; ?>;">
                    <label>¿De qué se trata?:
                        <input type="text" name="descripcion_emprendimiento"
                            value="<?php echo htmlspecialchars($emprendimientoT['descripcion']); ?>"></label>
                </div>
            </fieldset>



            <!-- 7. ¿Tiene Mascotas? -->
            <fieldset>
                <legend>7. ¿Tiene Mascotas?</legend>
                <label>Si <input type="radio" class="inputRadio" name="mascota" value="si"
                        onclick="handleRadioChangeM(this)" <?php echo !empty($mascotasT) ? 'checked' : '' ; ?>></label>
                <label>No <input type="radio" class="inputRadio" name="mascota" value="no"
                        onclick="handleRadioChangeM(this)" <?php echo empty($mascotasT) ? 'checked' : '' ;
                        ?>></label><br>

                <div id="contenedor_mascotas" style="display: <?php echo !empty($mascotasT) ? 'block' : 'none'; ?>;">
                    <div class="table-responsive">
                        <table id="mascotas">
                            <thead id="cabecera_mascotas">
                                <tr>
                                    <th>¿Qué tipo de mascota?:</th>
                                    <th>¿Cuántas?:</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($mascotasT as $mascota): ?>
                                <tr>
                                    <td><input type="text" name="tipo_mascota[]"
                                            value="<?php echo htmlspecialchars($mascota['tipo_mascota']); ?>"></td>
                                    <td><input type="number" name="cantidad_mascota[]"
                                            value="<?php echo htmlspecialchars($mascota['cantidad']); ?>"></td>
                                    <td>
                                        <button type="button" onclick="eliminarFilaM(this)"><span
                                                class="texto-eliminar">Eliminar</span><i
                                                class="bi bi-trash icon-responsive"></button>
                                        <input type="hidden" value="<?php echo htmlspecialchars($mascota['id']); ?>">
                                        <input type="hidden" name="id_mascota[]"
                                            value="<?php echo htmlspecialchars($mascota['id']); ?>">
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" onclick="agregarFilaM()"><span class="texto-oculto">Agregar Mascota</span><i
                            class="bi bi-plus-lg icon-responsive"></i></button>
                </div>
            </fieldset> <!-- 7. Situacion economica -->

            <!-- 8.1 Directa -->
            <fieldset>
                <legend>8.1 Situación Económica - Directa</legend>

                <!-- La tabla se oculta si no hay datos -->
                <div class="table-responsive">
                    <table id="ingresos_familiares" border="1"
                        style="<?php echo empty($ingresos) ? 'display:none;' : ''; ?>">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Monto</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($ingresos)): ?>
                            <?php foreach ($ingresos as $ingreso): ?>
                            <tr>
                                <td><input type="text" name="nombre_ingreso[]"
                                        value="<?php echo htmlspecialchars($ingreso['nombre_persona']); ?>"></td>
                                <td>
                                    <select id="montoIngreso" name="monto_ingreso[]">
                                        <option value="" <?php echo (isset($ingreso['monto']) && $ingreso['monto']=='' )
                                            ? 'selected' : '' ; ?>>Seleccionar</option>
                                        <option value="$460.000 -> $700.000" <?php echo
                                            ($ingreso['monto']=='$460.000 -> $700.000' ) ? 'selected' : '' ; ?>>$460.000
                                            ->
                                            $700.000</option>
                                        <option value="$700.001 -> $1.000.000" <?php echo
                                            ($ingreso['monto']=='$700.001 -> $1.000.000' ) ? 'selected' : '' ; ?>
                                            >$700.001
                                            -> $1.000.000</option>
                                        <option value="$1.000.001 -> $1.500.000" <?php echo
                                            ($ingreso['monto']=='$1.000.001 -> $1.500.000' ) ? 'selected' : '' ; ?>
                                            >$1.000.001 -> $1.500.000</option>
                                        <option value="$1.500.001 -> $2.000.000" <?php echo
                                            ($ingreso['monto']=='$1.500.001 -> $2.000.000' ) ? 'selected' : '' ; ?>
                                            >$1.500.001 -> $2.000.000</option>
                                        <option value="$2.000.001 -> $2.500.000" <?php echo
                                            ($ingreso['monto']=='$2.000.001 -> $2.500.000' ) ? 'selected' : '' ; ?>
                                            >$2.000.001 -> $2.500.000</option>
                                        <option value="$2.500.000" <?php echo ($ingreso['monto']=='$2.500.000' )
                                            ? 'selected' : '' ; ?>>$2.500.000</option>
                                    </select>
                                </td>
                                <td>
                                    <button type="button" onclick="eliminarFilaI(this)"><span
                                            class="texto-eliminar">Eliminar</span><i
                                            class="bi bi-trash icon-responsive"></button>
                                    <input type="hidden" value="<?php echo htmlspecialchars($ingreso['id']); ?>">
                                    <input type="hidden" name="id_ingreso[]"
                                        value="<?php echo htmlspecialchars($ingreso['id']); ?>">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mensaje de "No hay ingresos registrados" -->
                <p id="no-ingresos-msg" style="display: <?php echo empty($ingresos) ? 'block' : 'none'; ?>;">No hay
                    ingresos registrados.</p>

                <button type="button" onclick="agregarFilaI('ingresos_familiares')"><span class="texto-oculto">Agregar
                        Persona con
                        Ingreso</span><i class="bi bi-plus-lg icon-responsive"></i></button>
            </fieldset>


            <!-- 8.2 Egresos importantes -->
            <fieldset>
                <legend>8.2 Situación Económica - Egresos importantes</legend>

                <!-- La tabla se oculta si no hay datos -->
                <div class="table-responsive">
                    <table id="egresos_importantes" border="1"
                        style="<?php echo empty($egresos) ? 'display:none;' : ''; ?>">
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th>Monto</th>
                                <th>Observaciones</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($egresos)): ?>
                            <?php foreach ($egresos as $egreso): ?>
                            <tr>
                                <td><input type="text" name="descripcion_egreso[]"
                                        value="<?php echo htmlspecialchars($egreso['descripcion']); ?>"></td>
                                <td><input type="number" name="monto_egreso[]" class="monto_egreso"
                                        value="<?php echo htmlspecialchars($egreso['monto']); ?>"
                                        oninput="calcularTotalEgresos()"></td>
                                <td><input type="text" name="observacion_egreso[]"
                                        value="<?php echo htmlspecialchars($egreso['observaciones']); ?>"></td>
                                <td>
                                    <button type="button" onclick="eliminarFilaE(this)"><span
                                            class="texto-eliminar">Eliminar</span><i
                                            class="bi bi-trash icon-responsive"></button>
                                    <input type="hidden" value="<?php echo htmlspecialchars($egreso['id']); ?>">
                                    <input type="hidden" name="id_egreso[]"
                                        value="<?php echo htmlspecialchars($egreso['id']); ?>">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mensaje de "No hay egresos registrados" -->
                <p id="no-egresos-msg" style="display: <?php echo empty($egresos) ? 'block' : 'none'; ?>;">No hay
                    egresos registrados.</p>

                <button type="button" onclick="agregarFilaE('egresos_importantes')"><span class="texto-oculto">Agregar
                        Egreso</span><i class="bi bi-plus-lg icon-responsive"></i></button>

                <label>Total Egresos: <input type="number" id="total_egresos" readonly></label>
            </fieldset>

            <!-- 9. Condiciones de Habitabilidad -->
            <fieldset>
                <legend>9. Condiciones de Habitabilidad</legend>

                <label for="tipo_vivienda">Tipo de Vivienda:</label>
                <select name="tipo_vivienda" id="tipo_vivienda">
                    <option value="seleccionar" <?php echo (isset($habitalidad['tipo_vivienda']) &&
                        $habitalidad['tipo_vivienda']=='seleccionar' ) ? 'selected' : '' ; ?>>Seleccionar</option>

                    <option value="propia" <?php echo (isset($habitalidad['tipo_vivienda']) &&
                        $habitalidad['tipo_vivienda']=='propia' ) ? 'selected' : '' ; ?>>Propia</option>

                    <option value="arriendo" <?php echo (isset($habitalidad['tipo_vivienda']) &&
                        $habitalidad['tipo_vivienda']=='arriendo' ) ? 'selected' : '' ; ?>>Arriendo</option>

                    <option value="cedida" <?php echo (isset($habitalidad['tipo_vivienda']) &&
                        $habitalidad['tipo_vivienda']=='cedida' ) ? 'selected' : '' ; ?>>Cedida</option>

                    <option value="otro" <?php echo (isset($habitalidad['tipo_vivienda']) &&
                        $habitalidad['tipo_vivienda']=='otro' ) ? 'selected' : '' ; ?>>Otro</option>
                </select>

                <label for="material_vivienda">Material de la Vivienda:</label>
                <select name="material_vivienda" id="material_vivienda">
                    <option value="seleccionar" <?php echo (isset($habitalidad['material_vivienda']) &&
                        $habitalidad['material_vivienda']=='seleccionar' ) ? 'selected' : '' ; ?>>Seleccionar</option>
                    <option value="fuerte" <?php echo (isset($habitalidad['material_vivienda']) &&
                        $habitalidad['material_vivienda']=='fuerte' ) ? 'selected' : '' ; ?>>Fuerte</option>
                    <option value="ligero" <?php echo (isset($habitalidad['material_vivienda']) &&
                        $habitalidad['material_vivienda']=='ligero' ) ? 'selected' : '' ; ?>>Ligero</option>
                    <option value="madera" <?php echo (isset($habitalidad['material_vivienda']) &&
                        $habitalidad['material_vivienda']=='madera' ) ? 'selected' : '' ; ?>>Madera</option>
                    <option value="otro" <?php echo (isset($habitalidad['material_vivienda']) &&
                        $habitalidad['material_vivienda']=='otro' ) ? 'selected' : '' ; ?>>Otro</option>
                </select>

                <label for="numero_habitaciones">Número de Habitaciones:</label>
                <input type="number" name="numero_habitaciones" id="numero_habitaciones"
                    value="<?php echo isset($habitalidad['num_habitaciones']) ? $habitalidad['num_habitaciones'] : 0; ?>">


                <label for="numero_banos">Número de Baños:</label>
                <input type="number" name="numero_banos" id="numero_banos"
                    value="<?php echo isset($habitalidad['num_banos']) ? htmlspecialchars($habitalidad['num_banos']) : 0; ?>">


                <label for="cocina">Cocina:</label>
                <input type="number" name="cocina" id="cocina"
                    value="<?php echo isset($habitalidad['num_cocina']) ? htmlspecialchars($habitalidad['num_cocina']) : 0; ?>">


                <label for="logia">Logia:</label>
                <input type="number" name="logia" id="logia"
                    value="<?php echo isset($habitalidad['num_logia']) ? htmlspecialchars($habitalidad['num_logia']) : 0; ?>">


                <label for="condiciones_habitabilidad">Condiciones de Habitabilidad:</label>
                <select name="condiciones_habitabilidad" id="condiciones_habitabilidad" onchange="showInput('condiciones_habitabilidad_other')">
                    <option value="">Seleccionar</option>
                    <option value="normal" <?php echo (isset($habitalidad['condiciones_habitabilidad']) &&
                        $habitalidad['condiciones_habitabilidad']=='normal' ) ? 'selected' : '' ; ?>>Normal</option>
                    <option value="hacinamiento" <?php echo (isset($habitalidad['condiciones_habitabilidad']) &&
                        $habitalidad['condiciones_habitabilidad']=='hacinamiento' ) ? 'selected' : '' ; ?>>Hacinamiento
                    </option>
                    <option value="otro" <?php echo (isset($habitalidad['condiciones_habitabilidad']) &&
                        $habitalidad['condiciones_habitabilidad']=='otro' ) ? 'selected' : '' ; ?>>Otro</option>
                </select>
                <input type="text" name="condiciones_habitabilidad_other" id="condiciones_habitabilidad_other" placeholder="Especificar" style="display:none;">
            </fieldset>
            <!-- 10. Mapa conceptual -->
            <fieldset>
                <legend>10. Mapa conceptual</legend>
                <p>(Herramienta para la comprensión del entorno en que se desarrolla la vida del trabajador y su
                    familia)
                </p>
                <!-- Este espacio está reservado para incluir un mapa conceptual o un campo donde el trabajador pueda describir su entorno -->
                <textarea name="mapa_conceptual" rows="3" cols="80"
                    placeholder="Describe aquí el entorno en el que se desarrolla la vida del trabajador y su familia..."><?php echo htmlspecialchars($mapaConceptual['mapa_conceptual']); ?></textarea>
            </fieldset>


            <!-- 11. Otros -->
            <fieldset>
                <legend>11. Otros</legend>
                <textarea name="otros" rows="3" cols="80"
                    placeholder="Agregar cualquier otra información relevante..."><?php echo htmlspecialchars($otros['descripcion']); ?></textarea>
            </fieldset>


            <!-- 12. ¿Qué beneficios valora de parte de la empresa? -->
            <fieldset>
                <legend>12. ¿Qué beneficios valora de parte de la empresa?</legend>
                <textarea name="beneficios_valora" rows="3" cols="80"
                    placeholder="Escribe aquí los beneficios que valoras de la empresa..."><?php echo htmlspecialchars($beneficioV['beneficio']); ?></textarea>
            </fieldset>

            <!-- 13. ¿Qué beneficios no tenemos y considera son necesarios? -->
            <fieldset>
                <legend>13. ¿Qué beneficios no tenemos y considera son necesarios?</legend>
                <textarea name="beneficios_necesarios" rows="3" cols="80"
                    placeholder="Escribe aquí los beneficios que consideras necesarios..."><?php echo htmlspecialchars($beneficioN['beneficio']); ?></textarea>
            </fieldset>
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
                        <td>Cáncer, tumores, pólipos, nódulos, enfermedad de los ganglios linfáticos, leucemia,
                            linfomas,
                            aplasia medular.</td>
                        <td><input type="radio" name="salud_cancer" value="si" <?php echo
                                (isset($declaracionSalud['salud_cancer']) && $declaracionSalud['salud_cancer']==='si' )
                                ? 'checked' : '' ; ?>></td>
                        <td><input type="radio" name="salud_cancer" value="no" <?php echo
                                (isset($declaracionSalud['salud_cancer']) && $declaracionSalud['salud_cancer']==='no' )
                                ? 'checked' : '' ; ?>></td>
                    </tr>

                    <tr>
                        <td>Epilepsia, parkinson, neuropatías, esclerosis múltiple, parálisis, accidentes
                            cerebrovasculares,
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
                        <td>Depresión, esquizofrenia, trastornos del ánimo o de la personalidad, psicosis, crisis de
                            pánico,
                            bulimia, anorexia u otra enfermedad de salud mental.</td>
                        <td><input type="radio" name="salud_salud_mental" value="si" <?php echo
                                (isset($declaracionSalud['salud_salud_mental']) &&
                                $declaracionSalud['salud_salud_mental']==='si' ) ? 'checked' : '' ; ?>></td>
                        <td><input type="radio" name="salud_salud_mental" value="no" <?php echo
                                (isset($declaracionSalud['salud_salud_mental']) &&
                                $declaracionSalud['salud_salud_mental']==='no' ) ? 'checked' : '' ; ?>></td>

                    </tr>

                    <tr>
                        <td>Miopía, astigmatismo, hipermetropía, glaucoma, cataratas, estrabismo, queratocono, ceguera,
                            o
                            cualquier enfermedad a los ojos.</td>
                        <td><input type="radio" name="salud_ojo" value="si" <?php echo
                                (isset($declaracionSalud['salud_ojo']) && $declaracionSalud['salud_ojo']==='si' )
                                ? 'checked' : '' ; ?>></td>
                        <td><input type="radio" name="salud_ojo" value="no" <?php echo
                                (isset($declaracionSalud['salud_ojo']) && $declaracionSalud['salud_ojo']==='no' )
                                ? 'checked' : '' ; ?>></td>

                    </tr>

                    <tr>
                        <td>Desviación tabique nasal, obstrucción nasal, sordera, hipoacusia, o cualquier otra
                            enfermedad
                            que afecta a la nariz o los oídos.</td>
                        <td><input type="radio" name="salud_nariz_oidos" value="si" <?php echo
                                (isset($declaracionSalud['salud_nariz_oidos']) &&
                                $declaracionSalud['salud_nariz_oidos']==='si' ) ? 'checked' : '' ; ?>></td>
                        <td><input type="radio" name="salud_nariz_oidos" value="no" <?php echo
                                (isset($declaracionSalud['salud_nariz_oidos']) &&
                                $declaracionSalud['salud_nariz_oidos']==='no' ) ? 'checked' : '' ; ?>></td>

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
                        <td>Hipertensión arterial, angina, infarto al miocardio, by-pass o angioplastia, soplos,
                            enfermedad
                            reumática, arritmias, portador de marcapaso, insuficiencia cardíaca, enfermedad de las
                            válvulas
                            del corazón o cualquier otra enfermedad del corazón.</td>
                        <td><input type="radio" name="salud_corazon" value="si" <?php echo
                                (isset($declaracionSalud['salud_corazon']) && $declaracionSalud['salud_corazon']==='si'
                                ) ? 'checked' : '' ; ?>></td>
                        <td><input type="radio" name="salud_corazon" value="no" <?php echo
                                (isset($declaracionSalud['salud_corazon']) && $declaracionSalud['salud_corazon']==='no'
                                ) ? 'checked' : '' ; ?>></td>

                    </tr>

                    <tr>
                        <td>Aneurisma aórtico, tromboflebitis, várices, tratamiento anticoagulante, trombosis venosa
                            profunda, claudicación intermitente, enfermedades del sistema vascular, alteraciones de
                            coagulación, trombofilia, hemofilia y/o enfermedades del bazo y médula ósea.</td>
                        <td><input type="radio" name="salud_vascular" value="si" <?php echo
                                (isset($declaracionSalud['salud_vascular']) &&
                                $declaracionSalud['salud_vascular']==='si' ) ? 'checked' : '' ; ?>></td>
                        <td><input type="radio" name="salud_vascular" value="no" <?php echo
                                (isset($declaracionSalud['salud_vascular']) &&
                                $declaracionSalud['salud_vascular']==='no' ) ? 'checked' : '' ; ?>></td>

                    </tr>

                    <tr>
                        <td>Síndrome metabólico, colesterol alto, triglicéridos altos, dislipidemia, hiperuricemia o
                            gota,
                            resistencia a la insulina, resistencia a la glucosa, diabetes mellitus, sobrepeso, obesidad,
                            u
                            otras alteraciones metabólicas.</td>
                        <td><input type="radio" name="salud_metabolico" value="si" <?php echo
                                (isset($declaracionSalud['salud_metabolico']) &&
                                $declaracionSalud['salud_metabolico']==='si' ) ? 'checked' : '' ; ?>></td>
                        <td><input type="radio" name="salud_metabolico" value="no" <?php echo
                                (isset($declaracionSalud['salud_metabolico']) &&
                                $declaracionSalud['salud_metabolico']==='no' ) ? 'checked' : '' ; ?>></td>

                    </tr>

                    <tr>
                        <td>Cirrosis, várices esofágicas, insuficiencia hepática, pancreatitis, enfermedad a la vesícula
                            biliar, esófago de Barret, colitis ulcerosa, enfermedad de Crohn, úlceras digestivas,
                            hemorragias digestivas, sangramiento anal, hernia hiatal, hernias abdominales e inguinales,
                            diverticulitis, pólipos de colon u otras enfermedades que afecten al sistema digestivo.</td>
                        <td><input type="radio" name="salud_digestivo" value="si" <?php echo
                                (isset($declaracionSalud['salud_digestivo']) &&
                                $declaracionSalud['salud_digestivo']==='si' ) ? 'checked' : '' ; ?>></td>
                        <td><input type="radio" name="salud_digestivo" value="no" <?php echo
                                (isset($declaracionSalud['salud_digestivo']) &&
                                $declaracionSalud['salud_digestivo']==='no' ) ? 'checked' : '' ; ?>></td>

                    </tr>

                    <tr>
                        <td>Hepatitis B, hepatitis C, síndrome de inmunodeficiencia adquirida (SIDA) (portador o
                            enfermo).
                        </td>
                        <td><input type="radio" name="salud_hepatitis_sida" value="si" <?php echo
                                (isset($declaracionSalud['salud_hepatitis_sida']) &&
                                $declaracionSalud['salud_hepatitis_sida']==='si' ) ? 'checked' : '' ; ?>></td>
                        <td><input type="radio" name="salud_hepatitis_sida" value="no" <?php echo
                                (isset($declaracionSalud['salud_hepatitis_sida']) &&
                                $declaracionSalud['salud_hepatitis_sida']==='no' ) ? 'checked' : '' ; ?>></td>

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
                        <td>Enfermedad de las mamas, mioma uterino, quistes ováricos, endometriosis, PAP alterados u
                            otras
                            enfermedades que afecten el aparato reproductor femenino.</td>
                        <td><input type="radio" name="salud_reproductor_femenino" value="si" <?php echo
                                (isset($declaracionSalud['salud_reproductor_femenino']) &&
                                $declaracionSalud['salud_reproductor_femenino']==='si' ) ? 'checked' : '' ; ?>></td>
                        <td><input type="radio" name="salud_reproductor_femenino" value="no" <?php echo
                                (isset($declaracionSalud['salud_reproductor_femenino']) &&
                                $declaracionSalud['salud_reproductor_femenino']==='no' ) ? 'checked' : '' ; ?>></td>

                    </tr>

                    <tr>
                        <td>Lupus eritromatoso, artritis reumatoidea, cirrosis hepática autoinmune, miastenia gravis,
                            tiroiditis de Hashimoto, esclerosis múltiple, esclerosis lateral amiotrófica,
                            poliorradiculopatía desmielinizante inflamatoria crónica, síndrome Guillain-Barré,
                            fibromialgia,
                            eritema nodoso u otras enfermedades autoinmunes.</td>
                        <td><input type="radio" name="salud_autoinmune" value="si" <?php echo
                                (isset($declaracionSalud['salud_autoinmune']) &&
                                $declaracionSalud['salud_autoinmune']==='si' ) ? 'checked' : '' ; ?>></td>
                        <td><input type="radio" name="salud_autoinmune" value="no" <?php echo
                                (isset($declaracionSalud['salud_autoinmune']) &&
                                $declaracionSalud['salud_autoinmune']==='no' ) ? 'checked' : '' ; ?>></td>

                    </tr>

                    <tr>
                        <td>Hipotiroidismo, hipertiroidismo, nódulos a la tiroides o bocio multinodular u otras
                            patologías
                            de tiroides.</td>
                        <td><input type="radio" name="salud_tiroides" value="si" <?php echo
                                (isset($declaracionSalud['salud_tiroides']) &&
                                $declaracionSalud['salud_tiroides']==='si' ) ? 'checked' : '' ; ?>></td>
                        <td><input type="radio" name="salud_tiroides" value="no" <?php echo
                                (isset($declaracionSalud['salud_tiroides']) &&
                                $declaracionSalud['salud_tiroides']==='no' ) ? 'checked' : '' ; ?>></td>

                    </tr>

                    <tr>
                        <td>Artrosis, osteoporosis, hernias de columna, escoliosis, espondilosis o discopatía,
                            meniscopatía
                            o lesiones a la rodilla.</td>
                        <td><input type="radio" name="salud_esqueletico" value="si" <?php echo
                                (isset($declaracionSalud['salud_esqueletico']) &&
                                $declaracionSalud['salud_esqueletico']==='si' ) ? 'checked' : '' ; ?>></td>
                        <td><input type="radio" name="salud_esqueletico" value="no" <?php echo
                                (isset($declaracionSalud['salud_esqueletico']) &&
                                $declaracionSalud['salud_esqueletico']==='no' ) ? 'checked' : '' ; ?>></td>

                    </tr>

                    <tr>
                        <td>Cardiopatías congénitas, fisura palatina o labio leporino, displasia de cadera, síndrome de
                            Down, o cualquier otra malformación o patología congénita.</td>
                        <td><input type="radio" name="salud_congenito" value="si" <?php echo
                                (isset($declaracionSalud['salud_congenito']) &&
                                $declaracionSalud['salud_congenito']==='si' ) ? 'checked' : '' ; ?>></td>
                        <td><input type="radio" name="salud_congenito" value="no" <?php echo
                                (isset($declaracionSalud['salud_congenito']) &&
                                $declaracionSalud['salud_congenito']==='no' ) ? 'checked' : '' ; ?>></td>

                    </tr>

                    <tr>
                        <td>Embarazo actual.</td>
                        <td><input type="radio" name="salud_embarazo" value="si" <?php echo
                                (isset($declaracionSalud['salud_embarazo']) &&
                                $declaracionSalud['salud_embarazo']==='si' ) ? 'checked' : '' ; ?>></td>
                        <td><input type="radio" name="salud_embarazo" value="no" <?php echo
                                (isset($declaracionSalud['salud_embarazo']) &&
                                $declaracionSalud['salud_embarazo']==='no' ) ? 'checked' : '' ; ?>></td>

                    </tr>
                </table>
            </fieldset>
            <!-- 15. Detalle de Enfermedades Adicionales -->
            <!-- 15. Detalle de Enfermedades Adicionales -->
            <fieldset>
                <legend>15. Detalles Adicionales de Salud de Usted o su Grupo Familiar</legend>
                <p>Si usted ha respondido afirmativamente en el cuestionario anterior, o si usted o alguien de su grupo
                    familiar, padece alguna otra enfermedad, patología o condición de salud que no haya sido antes
                    detallada, agradecemos completar el detalle señalado a continuación.</p>
                <div class="table-responsive">
                    <table id="enfermedades_adicionales" border="1"
                        style="<?php echo empty($infoPersonaE) ? 'display:none;' : ''; ?>">
                        <thead>
                            <tr>
                                <th>Nombre y Apellido</th>
                                <th>Enfermedad</th>
                                <th>Fecha Diagnóstico</th>
                                <th>Estado Actual</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($infoPersonaE)): ?>
                            <?php foreach ($infoPersonaE as $inforPersona): ?>
                            <tr>
                                <td><input type="text" name="nombre_persona[]"
                                        value="<?php echo htmlspecialchars($inforPersona['nombre_persona']); ?>"></td>
                                <td><input type="text" name="enfermedad[]"
                                        value="<?php echo htmlspecialchars($inforPersona['enfermedad']); ?>"></td>
                                <td><input type="date" name="fecha_diagnostico[]"
                                        value="<?php echo htmlspecialchars($inforPersona['fecha_diagnostico']); ?>">
                                </td>
                                <td>

                                <select name="estado_actual[]">

                                        <option value="Alta" <?php echo
                                            ($inforPersona['estado_actual']=='Alta' ) ? 'selected' : '' ; ?>>Alta</option>
                                        <option value="Tratamiento" <?php echo
                                            ($inforPersona['estado_actual']=='Tratamiento' ) ? 'selected' : '' ; ?>
                                            >Tratamiento</option>
                                        <option value="Seguimiento" <?php echo
                                            ($inforPersona['estado_actual']=='Seguimiento' ) ? 'selected' : '' ; ?>
                                            >Seguimiento</option>
                                    </select>


                                </td>
                                <td>
                                    <button type="button" onclick="eliminarFila(this)"><span
                                            class="texto-eliminar">Eliminar</span><i
                                            class="bi bi-trash icon-responsive"></button>
                                    <input type="hidden" name="id_enfermedad[]"
                                        value="<?php echo htmlspecialchars($inforPersona['id']); ?>">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mensaje de "No hay enfermedades registradas" -->
                <p id="no-enfermedades-msg" style="display: <?php echo empty($infoPersonaE) ? 'block' : 'none'; ?>;">No
                    hay
                    enfermedades registradas.</p>

                <button type="button" onclick="agregarFilaEA()"><span class="texto-oculto">Agregar Persona</span><i
                        class="bi bi-plus-lg icon-responsive"></i></button>
            </fieldset>

            <!-- Button trigger modal -->
            <div class="cont-btn-fichasocial">
                <button type="submit" class=" btn-actualizar" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Actualizar
                </button>
                <a href="reportes.php?id=<?php echo $datostrabajador['id']; ?>" class="btn-pdf" target="_blank">Exportar en PDF</a>


            </div>

        </form>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 colorletra-modal" id="exampleModalLabel">¿Estas seguro de guardar estos datos?</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">NO</button>
                        <button type="button" class="btn btn-primary" id="confirmButton">SI</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

<script src="js/scriptModal.js" defer></script>
<script src="js/scriptFormulario.js" defer></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

</html>