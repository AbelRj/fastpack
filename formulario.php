<?php 
include("bd.php");

if(isset($_GET['id'])){
    //Recuperar los datos del ID correspondiente ( seleccionado )
$idTrabajador=(isset($_GET['id']))?$_GET['id']:"";
$sentencia=$conexion->prepare("SELECT * FROM trabajador WHERE id=:id ");
$sentencia->bindParam(":id",$idTrabajador);
$sentencia->execute();
$datostrabajador=$sentencia->fetch(PDO::FETCH_LAZY);
   // Consulta para obtener todos los miembros del grupo familiar asociados al trabajador
$sentencia = $conexion->prepare("SELECT * FROM grupo_familiar WHERE trabajador_id = :trabajador_id");
$sentencia->bindParam(":trabajador_id", $idTrabajador);
$sentencia->execute();
$datosGFamiliar = $sentencia->fetchAll(PDO::FETCH_ASSOC);

$sentencia = $conexion->prepare("SELECT * FROM nivel_educacional_familiar WHERE grupo_familiar_id = :grupo_familiar_id");
$sentencia->bindParam(":grupo_familiar_id", $grupo_familiar_id);
$sentencia->execute();
$datosGFamiliar = $sentencia->fetch(PDO::FETCH_LAZY);

}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="styleFormulario.css">

    <title>Ficha Social Familiar – Trabajadores Fastpack</title>

</head>

<body>
    <h1>Ficha Social Familiar – Trabajadores Fastpack</h1>
    <div><a type="button" href="index.php">Atrás</a>
        <button id="enableButton">Editar</button>
    </div>

    <form id="form" method="POST" action="code.php">
        <!-- 1. Identificación del Trabajador -->
        <fieldset>
            <legend>1. Identificación del Trabajador</legend>
            <label>ID: <input readonly type="text" name="id"
                    value="<?php echo htmlspecialchars($datostrabajador['id']); ?>" required></label><br>

            <label>Nombre y Apellido: <input readonly type="text" name="nombre_apellido"
                    value="<?php echo htmlspecialchars($datostrabajador['nombre_apellido']); ?>" required></label><br>
            <label>Fecha de Nacimiento: <input readonly type="date" name="fecha_nacimiento"
                    value="<?php echo htmlspecialchars($datostrabajador['fecha_nacimiento']); ?>" required></label><br>
            <label>Nacionalidad: <input readonly type="text" name="nacionalidad"
                    value="<?php echo htmlspecialchars($datostrabajador['nacionalidad']); ?>" required></label><br>
            <label>Domicilio: <input readonly type="text" name="domicilio"
                    value="<?php echo htmlspecialchars($datostrabajador['domicilio']); ?>" required></label><br>
            <label>Teléfono: <input readonly type="tel" name="telefono"
                    value="<?php echo htmlspecialchars($datostrabajador['telefono']); ?>" required></label><br>
            <label>Correo Electrónico: <input readonly type="email" name="correo"
                    value="<?php echo htmlspecialchars($datostrabajador['correo']); ?>" required></label><br>
            <label>Estado Civil: <input readonly type="text" name="estado_civil"
                    value="<?php echo htmlspecialchars($datostrabajador['estado_civil']); ?>" required></label><br>
            <label>Previsión de Salud: <input readonly type="text" name="prevision_salud"
                    value="<?php echo htmlspecialchars($datostrabajador['prevision_salud']); ?>" required></label><br>
        </fieldset>
        <!-- 2. Grupo Familiar -->
        <fieldset>
            <legend>2. Grupo Familiar</legend>
            <table id="grupo_familiar" border="1">
                <thead>
                    <tr>
                        <th>Nombre y Apellido</th>
                        <th>Parentesco</th>
                        <th>Fecha de Nacimiento</th>
                        <th>Sexo M/F</th>
                        <th>Estado Civil</th>
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
                        <td><input type="text" name="actividad_familiar[]"
                                value="<?php echo htmlspecialchars($familiar['actividad']); ?>"></td>
                        <td><button type="button" onclick="eliminarFila(this)">Eliminar</button></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="8">No hay miembros en el grupo familiar.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <button type="button" onclick="agregarFilaGF('grupo_familiar')">Agregar Miembro Familiar</button>
        </fieldset>
        <!-- 3. Nivel Educacional Familiar -->
        <fieldset>
            <legend>3. Nivel Educacional Familiar</legend>
            <table id="nivel_educacional" border="1">
                <thead>
                    <tr>
                        <th>Nombre y Apellido</th>
                        <th>Parentesco</th>
                        <th>Nivel</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="nombre_apellido_educacion_1"></td>
                        <td><input type="text" name="parentesco_educacion_1"></td>
                        <td><input type="text" name="nivel_educacional_1"></td>
                        <td><button type="button" onclick="eliminarFila(this)">Eliminar</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" onclick="agregarFilaNEF('nivel_educacional')">Agregar Nivel Educacional</button>
        </fieldset>
        <!-- 4. Historia Familiar -->
        <fieldset>
            <legend>4. Historia Familiar (en la actualidad)</legend>
            <textarea name="historia_familiar" rows="5" cols="50"></textarea>
        </fieldset>

        <!-- 5. ¿Apoya a algún familiar económicamente? -->
        <fieldset>
            <legend>5. ¿Apoya a algún familiar económicamente?</legend>
            <label>Si <input type="radio" name="apoyo_economico" value="si"></label>
            <label>No <input type="radio" name="apoyo_economico" value="no"></label><br>

            <table id="apoyo_economico" border="1">
                <thead>
                    <tr>
                        <th>¿A quién?</th>
                        <th>Motivo</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="a_quien_apoya_1"></td>
                        <td><input type="text" name="motivo_apoyo_1"></td>
                        <td><button type="button" onclick="eliminarFila(this)">Eliminar</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" onclick="agregarFilaAPF('apoyo_economico')">Agregar Familiar Apoyado</button>
        </fieldset>


        <!-- 6. ¿Tiene algún emprendimiento? -->
        <fieldset>
            <legend>6. ¿Tiene algún emprendimiento?</legend>
            <label>Si<input type="radio" name="emprendimiento" value="si"></label>
            <label>No<input type="radio" name="emprendimiento" value="no"></label><br>
            <label>¿De qué se trata?: <input type="text" name="descripcion_emprendimiento"></label>
        </fieldset>

        <!-- 7. ¿Tiene Mascotas? -->
        <fieldset>
            <legend>7. ¿Tiene Mascotas?</legend>
            <label>Si<input type="radio" name="mascotas" value="si"></label>
            <label>No<input type="radio" name="mascotas" value="no"></label><br>
            <label>¿Qué tipo de mascota?: <input type="text" name="tipo_mascota"></label><br>
            <label>¿Cuántas?: <input type="number" name="cantidad_mascotas"></label>
        </fieldset>
        <!-- 7. Situacion economica -->
        <fieldset>
            <legend>8. Situación Económica</legend>

            <!-- 8.1 Directa -->
            <fieldset>
                <legend>8.1 Directa</legend>

                <table id="ingresos_familiares" border="1">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Monto</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="nombre_ingreso_1"></td>
                            <td><input type="number" name="monto_ingreso_1" class="monto_ingreso"
                                    oninput="calcularTotal()"></td>
                            <td><button type="button" onclick="eliminarFila(this)">Eliminar</button></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" onclick="agregarFilaSEDirecta('ingresos_familiares')">Agregar Persona con
                    Ingreso</button><br><br>

                <label>Total Ingreso Grupo Familiar: <input type="number" id="total_ingreso_familiar"
                        readonly></label><br><br>

                <label>Rangos Ingreso Grupo Familiar:</label><br>
                <p>$ 460.000 -> $ 700.000</p>
                <p>$ 700.001 -> $ 1.000.000</p>
                <p>$ 1.000.001 -> $ 1.500.000</p>
                <p>$ 1.500.001 -> $ 2.000.000</p>
                <p>$ 2.000.001 -> $ 2.500.000</p>
                <p>> $ 2.500.000</p>
            </fieldset>
            <!-- 8.2 Egresos importantes -->
            <fieldset>
                <legend>8.2 Egresos importantes</legend>

                <table id="egresos_importantes" border="1">
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th>Monto</th>
                            <th>Observaciones</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="descripcion_egreso_1"></td>
                            <td><input type="number" name="monto_egreso_1" class="monto_egreso"
                                    oninput="calcularTotalEgresos()"></td>
                            <td><input type="text" name="observacion_egreso_1"></td>
                            <td><button type="button" onclick="eliminarFila(this)">Eliminar</button></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" onclick="agregarFilaEI('egresos_importantes')">Agregar Egreso</button><br><br>

                <label>Total Egresos: <input type="number" id="total_egresos" readonly></label>
            </fieldset>
        </fieldset>
        <!-- 9. Condiciones de Habitabilidad -->
        <fieldset>
            <legend>9. Condiciones de Habitabilidad</legend>

            <label for="tipo_vivienda">Tipo de Vivienda:</label>
            <select name="tipo_vivienda" id="tipo_vivienda">
                <option value="propia">Propia</option>
                <option value="arriendo">Arriendo</option>
                <option value="cedida">Cedida</option>
                <option value="otro">Otro</option>
            </select><br><br>

            <label for="material_vivienda">Material de la Vivienda:</label>
            <select name="material_vivienda" id="material_vivienda">
                <option value="fuerte">Fuerte</option>
                <option value="ligero">Ligero</option>
                <option value="madera">Madera</option>
                <option value="otro">Otro</option>
            </select><br><br>

            <label for="numero_habitaciones">Número de Habitaciones:</label>
            <input type="number" name="numero_habitaciones" id="numero_habitaciones"><br><br>

            <label for="numero_banos">Número de Baños:</label>
            <input type="number" name="numero_banos" id="numero_banos"><br><br>

            <label for="cocina">Cocina:</label>
            <input type="number" name="cocina" id="cocina"><br><br>

            <label for="logia">Logia:</label>
            <input type="number" name="logia" id="logia"><br><br>

            <label for="condiciones_habitabilidad">Condiciones de Habitabilidad:</label>
            <select name="condiciones_habitabilidad" id="condiciones_habitabilidad">
                <option value="normal">Normal</option>
                <option value="hacinamiento">Hacinamiento</option>
                <option value="otro">Otro</option>
            </select>
        </fieldset>
        <!-- 10. Mapa conceptual -->
        <fieldset>
            <legend>10. Mapa conceptual</legend>
            <p>(Herramienta para la comprensión del entorno en que se desarrolla la vida del trabajador y su familia)
            </p>
            <!-- Este espacio está reservado para incluir un mapa conceptual o un campo donde el trabajador pueda describir su entorno -->
            <textarea name="mapa_conceptual" rows="10" cols="80"
                placeholder="Describe aquí el entorno en el que se desarrolla la vida del trabajador y su familia..."></textarea>
        </fieldset>

        <br>

        <!-- 11. Otros -->
        <fieldset>
            <legend>11. Otros</legend>
            <textarea name="otros" rows="10" cols="80"
                placeholder="Agregar cualquier otra información relevante..."></textarea>
        </fieldset>

        <br>

        <!-- 12. ¿Qué beneficios valora de parte de la empresa? -->
        <fieldset>
            <legend>12. ¿Qué beneficios valora de parte de la empresa?</legend>
            <textarea name="beneficios_valora" rows="5" cols="80"
                placeholder="Escribe aquí los beneficios que valoras de la empresa..."></textarea>
        </fieldset>

        <br>

        <!-- 13. ¿Qué beneficios no tenemos y considera son necesarios? -->
        <fieldset>
            <legend>13. ¿Qué beneficios no tenemos y considera son necesarios?</legend>
            <textarea name="beneficios_necesarios" rows="5" cols="80"
                placeholder="Escribe aquí los beneficios que consideras necesarios..."></textarea>
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
                    <td>Cáncer, tumores, pólipos, nódulos, enfermedad de los ganglios linfáticos, leucemia, linfomas,
                        aplasia medular.</td>
                    <td><input type="radio" name="salud_cancer" value="si"></td>
                    <td><input type="radio" name="salud_cancer" value="no"></td>
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
                    <td><input type="radio" name="salud_sistema_nervioso" value="si"></td>
                    <td><input type="radio" name="salud_sistema_nervioso" value="no"></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Depresión, esquizofrenia, trastornos del ánimo o de la personalidad, psicosis, crisis de pánico,
                        bulimia, anorexia u otra enfermedad de salud mental.</td>
                    <td><input type="radio" name="salud_salud_mental" value="si"></td>
                    <td><input type="radio" name="salud_salud_mental" value="no"></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Miopía, astigmatismo, hipermetropía, glaucoma, cataratas, estrabismo, queratocono, ceguera, o
                        cualquier enfermedad a los ojos.</td>
                    <td><input type="radio" name="salud_ojo" value="si"></td>
                    <td><input type="radio" name="salud_ojo" value="no"></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Desviación tabique nasal, obstrucción nasal, sordera, hipoacusia, o cualquier otra enfermedad
                        que afecta a la nariz o los oídos.</td>
                    <td><input type="radio" name="salud_nariz_oidos" value="si"></td>
                    <td><input type="radio" name="salud_nariz_oidos" value="no"></td>
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
                    <td><input type="radio" name="salud_respiratorio" value="si"></td>
                    <td><input type="radio" name="salud_respiratorio" value="no"></td>
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
                    <td><input type="radio" name="salud_corazon" value="si"></td>
                    <td><input type="radio" name="salud_corazon" value="no"></td>
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
                    <td><input type="radio" name="salud_vascular" value="si"></td>
                    <td><input type="radio" name="salud_vascular" value="no"></td>
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
                    <td><input type="radio" name="salud_metabolico" value="si"></td>
                    <td><input type="radio" name="salud_metabolico" value="no"></td>
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
                    <td><input type="radio" name="salud_digestivo" value="si"></td>
                    <td><input type="radio" name="salud_digestivo" value="no"></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Hepatitis B, hepatitis C, síndrome de inmunodeficiencia adquirida (SIDA) (portador o enfermo).
                    </td>
                    <td><input type="radio" name="salud_hepatitis_sida" value="si"></td>
                    <td><input type="radio" name="salud_hepatitis_sida" value="no"></td>
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
                    <td><input type="radio" name="salud_renal" value="si"></td>
                    <td><input type="radio" name="salud_renal" value="no"></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Enfermedad de las mamas, mioma uterino, quistes ováricos, endometriosis, PAP alterados u otras
                        enfermedades que afecten el aparato reproductor femenino.</td>
                    <td><input type="radio" name="salud_reproductor_femenino" value="si"></td>
                    <td><input type="radio" name="salud_reproductor_femenino" value="no"></td>
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
                    <td><input type="radio" name="salud_autoinmune" value="si"></td>
                    <td><input type="radio" name="salud_autoinmune" value="no"></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Hipotiroidismo, hipertiroidismo, nódulos a la tiroides o bocio multinodular u otras patologías
                        de tiroides.</td>
                    <td><input type="radio" name="salud_tiroides" value="si"></td>
                    <td><input type="radio" name="salud_tiroides" value="no"></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Artrosis, osteoporosis, hernias de columna, escoliosis, espondilosis o discopatía, meniscopatía
                        o lesiones a la rodilla.</td>
                    <td><input type="radio" name="salud_esqueletico" value="si"></td>
                    <td><input type="radio" name="salud_esqueletico" value="no"></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Cardiopatías congénitas, fisura palatina o labio leporino, displasia de cadera, síndrome de
                        Down, o cualquier otra malformación o patología congénita.</td>
                    <td><input type="radio" name="salud_congenito" value="si"></td>
                    <td><input type="radio" name="salud_congenito" value="no"></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>Embarazo actual.</td>
                    <td><input type="radio" name="salud_embarazo" value="si"></td>
                    <td><input type="radio" name="salud_embarazo" value="no"></td>
                </tr>
            </table>
        </fieldset>
        <!-- 15. Detalle de Enfermedades Adicionales -->
        <fieldset>
            <legend>15. Detalles Adicionales de Salud de Usted o su Grupo Familiar</legend>
            <p>Si usted ha respondido afirmativamente en el cuestionario anterior, o si usted o alguien de su grupo
                familiar, padece alguna otra enfermedad, patología o condición de salud que no haya sido antes
                detallada, agradecemos completar el detalle señalado a continuación.</p>

            <table id="enfermedades_adicionales" border="1">
                <thead>
                    <tr>
                        <th>Nombre Persona Afectada</th>
                        <th>Enfermedad</th>
                        <th>Fecha Diagnóstico</th>
                        <th>Estado Actual (Alta, Tratamiento, Seguimiento)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="nombre_persona_1"></td>
                        <td><input type="text" name="enfermedad_1"></td>
                        <td><input type="date" name="fecha_diagnostico_1"></td>
                        <td><input type="text" name="estado_actual_1"></td>
                        <td><button type="button" onclick="eliminarFila(this)">Eliminar</button></td>
                    </tr>
                </tbody>
            </table>
            <br>
            <button type="button" onclick="agregarFilaDASF('enfermedades_adicionales')">Agregar Persona</button>
        </fieldset>
        <!-- Button trigger modal -->
        <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Actualizar
        </button>

    </form>
    <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">¿Estas seguro de guardar ests datos?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">NO</button>
        <button type="button" class="btn btn-primary" id="confirmButton">SI</button>
      </div>
    </div>
  </div>
</div>

</body>
<script src="scriptFormulario.js" defer></script>
<script src="scriptModal.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>