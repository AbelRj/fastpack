|<?php

include("bd.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    //Identificación del Trabajador
    $trabajador_id = isset($_POST['id']) ? $_POST['id'] : null;
    // Recuperar datos del formulario 
    $nombreApellidoT = (isset($_POST['nombre_apellido'])) ? $_POST['nombre_apellido']:"";
    $fechaNacimientoT = (isset($_POST['fecha_nacimiento'])) ? $_POST['fecha_nacimiento']:"";
    $nacionalidadT = (isset($_POST['nacionalidad'])) ? $_POST['nacionalidad']:"";
    $domicilioT = (isset($_POST['domicilio'])) ? $_POST['domicilio']:"";
    $telefonoT = (isset($_POST['telefono'])) ? $_POST['telefono']:"";
    $correoT = (isset($_POST['correo'])) ? $_POST['correo']:"";
    $estadoCivilT = (isset($_POST['estado_civil'])) ? $_POST['estado_civil']:"";
    $previsionSaludT = (isset($_POST['prevision_salud'])) ? $_POST['prevision_salud']:"";


    $sentencia = $conexion->prepare("UPDATE trabajador SET nombre_apellido = :nombre_apellido, fecha_nacimiento = :fecha_nacimiento, 
    nacionalidad = :nacionalidad, domicilio = :domicilio, telefono = :telefono, 
    correo_electronico = :correo_electronico, estado_civil = :estado_civil, prevision_salud = :prevision_salud 
    WHERE id = :id");
    $sentencia->bindParam(':id', $trabajador_id);
    $sentencia->bindParam(':nombre_apellido', $nombreApellidoT);
    $sentencia->bindParam(':fecha_nacimiento', $fechaNacimientoT);
    $sentencia->bindParam(':nacionalidad', $nacionalidadT);
    $sentencia->bindParam(':domicilio', $domicilioT);
    $sentencia->bindParam(':telefono', $telefonoT);
    $sentencia->bindParam(':correo_electronico', $correoT);
    $sentencia->bindParam(':estado_civil', $estadoCivilT);
    $sentencia->bindParam(':prevision_salud', $previsionSaludT);
    $sentencia->execute();

    //Grupo Familiar
    // Usar el primer valor de trabajador_id si hay múltiples
    $nombres_apellido = isset($_POST['nombre_apellido_familiar']) ? $_POST['nombre_apellido_familiar'] : [];
    $parentescos = isset($_POST['parentesco']) ? $_POST['parentesco'] : [];
    $fechas_nacimiento = isset($_POST['fecha_nacimiento_familiar']) ? $_POST['fecha_nacimiento_familiar'] : [];
    $sexos = isset($_POST['sexo_familiar']) ? $_POST['sexo_familiar'] : [];
    $estados_civil = isset($_POST['estado_civil_familiar']) ? $_POST['estado_civil_familiar'] : [];
    $niveles_educacionales = isset($_POST['nivel_educacional']) ? $_POST['nivel_educacional'] : [];
    $actividades = isset($_POST['actividad_familiar']) ? $_POST['actividad_familiar'] : [];
    $ids_familiares = isset($_POST['id_familiar']) ? $_POST['id_familiar'] : [];

   // Verificar que todos los arreglos tengan la misma longitud
   $num_familiares = count($nombres_apellido);
   if ($num_familiares !== count($parentescos) || 
       $num_familiares !== count($fechas_nacimiento) || 
       $num_familiares !== count($sexos) || 
       $num_familiares !== count($estados_civil) || 
       $num_familiares !== count($niveles_educacionales) || 
       $num_familiares !== count($actividades)) {
       echo "Error: Todos los campos deben tener la misma cantidad de entradas.";
       exit;
   }

    // Iterar sobre cada miembro del grupo familiar
   // Procesar cada fila del grupo familiar
for ($i = 0; $i < count($nombres_apellido); $i++) {
    $idFamiliar = $ids_familiares[$i];
    
    if ($idFamiliar === "new") {
        // Insertar nueva fila
        $sentencia = $conexion->prepare("INSERT INTO grupo_familiar 
            (trabajador_id, nombre_apellido, parentesco, fecha_nacimiento, sexo, estado_civil, nivel_educacional, actividad) 
            VALUES (:trabajador_id, :nombre_apellido, :parentesco, :fecha_nacimiento, :sexo, :estado_civil, :nivel_educacional, :actividad)");
    } else {
        // Actualizar fila existente
        $sentencia = $conexion->prepare("UPDATE grupo_familiar SET 
            nombre_apellido = :nombre_apellido, 
            parentesco = :parentesco, 
            fecha_nacimiento = :fecha_nacimiento, 
            sexo = :sexo, 
            estado_civil = :estado_civil, 
            nivel_educacional = :nivel_educacional, 
            actividad = :actividad 
            WHERE trabajador_id = :trabajador_id AND id = :id_familiar");
        $sentencia->bindParam(':id_familiar', $idFamiliar);
    }

    $sentencia->bindParam(':trabajador_id', $trabajador_id);
    $sentencia->bindParam(':nombre_apellido', $nombres_apellido[$i]);
    $sentencia->bindParam(':parentesco', $parentescos[$i]);
    $sentencia->bindParam(':fecha_nacimiento', $fechas_nacimiento[$i]);
    $sentencia->bindParam(':sexo', $sexos[$i]);
    $sentencia->bindParam(':estado_civil', $estados_civil[$i]);
    $sentencia->bindParam(':nivel_educacional', $niveles_educacionales[$i]);
    $sentencia->bindParam(':actividad', $actividades[$i]);

    $sentencia->execute();
}
         // Recuperar los IDs de los familiares a eliminar
         $eliminar_familiar = isset($_POST['eliminar_familiar']) ? $_POST['eliminar_familiar'] : [];
             // Procesar eliminaciones
     if (!empty($eliminar_familiar)) {
         foreach ($eliminar_familiar as $idFamiliar) {
             $sentencia = $conexion->prepare("DELETE FROM grupo_familiar WHERE id = :idFamiliar");
             $sentencia->bindParam(':idFamiliar', $idFamiliar);
             $sentencia->execute();
          
         }
     }

 // Historia Familiar (en la actualidad)
$historia = (isset($_POST['historia_familiar'])) ? $_POST['historia_familiar'] : "";

// Verificar si ya existe un registro de historia_familiar para este trabajador
$sentencia = $conexion->prepare("SELECT id FROM historia_familiar WHERE trabajador_id = :trabajador_id");
$sentencia->bindParam(':trabajador_id', $trabajador_id);
$sentencia->execute();
$historiaExistente = $sentencia->fetch(PDO::FETCH_ASSOC);

if ($historiaExistente) {
    // Si existe, hacer un UPDATE
    $sentencia = $conexion->prepare("UPDATE historia_familiar SET historia = :historia WHERE trabajador_id = :trabajador_id");
} else {
    // Si no existe, hacer un INSERT
    $sentencia = $conexion->prepare("INSERT INTO historia_familiar (trabajador_id, historia) VALUES (:trabajador_id, :historia)");
}

// Vincular los parámetros a la consulta SQL
$sentencia->bindParam(':trabajador_id', $trabajador_id);
$sentencia->bindParam(':historia', $historia);
$sentencia->execute();

// Apoyo economico
    $nombres_apellidosAE = isset($_POST['a_quien_apoya']) ? $_POST['a_quien_apoya'] : [];
    $motivosAE = isset($_POST['motivo_apoyo']) ? $_POST['motivo_apoyo'] : [];
    $ids_apoyoF = isset($_POST['id_apoyoF']) ? $_POST['id_apoyoF'] : null;
    $num_AETrabajador = count($nombres_apellidosAE);
    
    if ($num_AETrabajador!== count($motivosAE)) {
        echo "Error: Todos los campos deben tener la misma cantidad de entradas.";
        exit;
    }
    

// Iterar sobre cada persona apoyada
for ($i = 0; $i < $num_AETrabajador; $i++) {
    $nombre_apellidoAE = $nombres_apellidosAE[$i];
    $motivoAE = $motivosAE[$i];
    $id_apoyoF = isset($ids_apoyoF[$i]) ? $ids_apoyoF[$i] : null;

    // Verificar si la persona de apoyo económico ya existe
    $sentencia = $conexion->prepare("SELECT id FROM apoyo_economico WHERE trabajador_id = :trabajador_id AND id = :id");
    $sentencia->bindParam(':trabajador_id', $trabajador_id);
    $sentencia->bindParam(':id', $id_apoyoF);
    $sentencia->execute();
    $persona_AE = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($persona_AE) {
        // Actualización
        $sentencia = $conexion->prepare("UPDATE apoyo_economico SET a_quien = :nombre_apoyoF, motivo = :motivo WHERE trabajador_id = :trabajador_id AND id = :id");
        $sentencia->bindParam(':id', $id_apoyoF); // Aquí sí es correcto vincular el ID
    } else {
        // Inserción: no hay `:id` aquí
        $sentencia = $conexion->prepare("INSERT INTO apoyo_economico (trabajador_id, a_quien, motivo) VALUES (:trabajador_id, :nombre_apoyoF, :motivo)");
        // No se debe vincular el ID aquí, porque no hay marcador `:id`
    }

    // Vincular los valores de los campos a la consulta SQL
    $sentencia->bindParam(':trabajador_id', $trabajador_id);
    $sentencia->bindParam(':nombre_apoyoF', $nombre_apellidoAE);
    $sentencia->bindParam(':motivo', $motivoAE);
    $sentencia->execute();
}

$eliminar_apoyoFT = isset($_POST['eliminar_apoyoF']) ? $_POST['eliminar_apoyoF'] : [];

    // Procesar eliminaciones
if (!empty($eliminar_apoyoFT)) {
foreach ($eliminar_apoyoFT as $idapoyo) {
    $sentencia = $conexion->prepare("DELETE FROM apoyo_economico WHERE id = :idApoyoE");
    $sentencia->bindParam(':idApoyoE', $idapoyo);
    $sentencia->execute();
 
}
}
// Emprendimiento
// Recuperar el valor del radio y la descripción del emprendimiento
$emprendimiento = isset($_POST['descripcion_emprendimiento']) ? $_POST['descripcion_emprendimiento'] : "";

// Verificar si el usuario ha seleccionado "No" para el emprendimiento
if (isset($_POST['emprendimiento']) && $_POST['emprendimiento'] == 'no') {
    // Si se seleccionó "No", eliminar el registro de emprendimiento si existe
    $sentencia = $conexion->prepare("DELETE FROM emprendimiento WHERE trabajador_id = :trabajador_id");
    $sentencia->bindParam(':trabajador_id', $trabajador_id);
    $sentencia->execute();
} else {
    // Verificar si ya existe un registro de emprendimiento para este trabajador
    $sentencia = $conexion->prepare("SELECT id FROM emprendimiento WHERE trabajador_id = :trabajador_id");
    $sentencia->bindParam(':trabajador_id', $trabajador_id);
    $sentencia->execute();
    $emprendimientoExistente = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($emprendimientoExistente) {
        // Si existe, hacer un UPDATE
        $sentencia = $conexion->prepare("UPDATE emprendimiento SET descripcion = :descripcion WHERE trabajador_id = :trabajador_id");
    } else {
        // Si no existe, hacer un INSERT
        $sentencia = $conexion->prepare("INSERT INTO emprendimiento (trabajador_id, descripcion) VALUES (:trabajador_id, :descripcion)");
    }

    // Vincular los parámetros a la consulta SQL
    $sentencia->bindParam(':trabajador_id', $trabajador_id);
    $sentencia->bindParam(':descripcion', $emprendimiento);
    $sentencia->execute();
}
// Mascotas
$tipos_mascotasT = isset($_POST['tipo_mascota']) ? $_POST['tipo_mascota'] : [];
$cantidad_mascotasT = isset($_POST['cantidad_mascota']) ? $_POST['cantidad_mascota'] : [];
$ids_mascotas = isset($_POST['id_mascota']) ? $_POST['id_mascota'] : null;
$num_tipos_mascotasT = count($tipos_mascotasT);

if ($num_tipos_mascotasT !== count($cantidad_mascotasT)) {
    echo "Error: Todos los campos deben tener la misma cantidad de entradas.";
    exit;
}

// Iterar sobre cada mascota
for ($i = 0; $i < $num_tipos_mascotasT; $i++) {
    $tipo_mascotaT = $tipos_mascotasT[$i];
    $cantidad_mascotaT = $cantidad_mascotasT[$i];
    $id_mascota = isset($ids_mascotas[$i]) ? $ids_mascotas[$i] : null;

    // Verificar si la mascota ya existe
    if ($id_mascota) {
        $sentencia = $conexion->prepare("SELECT id FROM mascotas WHERE trabajador_id = :trabajador_id AND id = :id");
        $sentencia->bindParam(':trabajador_id', $trabajador_id);
        $sentencia->bindParam(':id', $id_mascota);
        $sentencia->execute();
        $mascotaT = $sentencia->fetch(PDO::FETCH_ASSOC);

        if ($mascotaT) {
            // Si la mascota ya existe, actualizar
            $sentencia = $conexion->prepare("UPDATE mascotas SET tipo_mascota = :tipo_mascota, cantidad = :cantidad_mascota WHERE trabajador_id = :trabajador_id AND id = :id");
        } else {
            // Si la mascota no existe, insertar
            $sentencia = $conexion->prepare("INSERT INTO mascotas (trabajador_id, tipo_mascota, cantidad) VALUES (:trabajador_id, :tipo_mascota, :cantidad_mascota)");
        }
    } else {
        // Si no hay ID, insertar nueva mascota
        $sentencia = $conexion->prepare("INSERT INTO mascotas (trabajador_id, tipo_mascota, cantidad) VALUES (:trabajador_id, :tipo_mascota, :cantidad_mascota)");
    }

    // Vincular los valores a la consulta SQL
    $sentencia->bindParam(':trabajador_id', $trabajador_id);
    if ($id_mascota) $sentencia->bindParam(':id', $id_mascota);
    $sentencia->bindParam(':tipo_mascota', $tipo_mascotaT);
    $sentencia->bindParam(':cantidad_mascota', $cantidad_mascotaT);
    $sentencia->execute();
}

// Eliminar mascotas
$eliminar_mascota = isset($_POST['eliminar_mascota']) ? $_POST['eliminar_mascota'] : [];

if (!empty($eliminar_mascota)) {
    foreach ($eliminar_mascota as $idMascota) {
        $sentencia = $conexion->prepare("DELETE FROM mascotas WHERE id = :idMascota");
        $sentencia->bindParam(':idMascota', $idMascota);
        $sentencia->execute();
    }
}


//Ingresos

$nombres_ingresoT = isset($_POST['nombre_ingreso']) ? $_POST['nombre_ingreso'] : [];
$cantidades_ingresoT = isset($_POST['monto_ingreso']) ? $_POST['monto_ingreso'] : [];
$ids_ingresos = isset($_POST['id_ingreso']) ? $_POST['id_ingreso'] : null;
$num_ingresos = count($nombres_ingresoT);

if ($num_ingresos!== count($cantidades_ingresoT)) {
    echo "Error: Todos los campos deben tener la misma cantidad de entradas.";
    exit;
}
// Iterar sobre cada persona apoyada
for ($i = 0; $i < $num_ingresos; $i++) {
    $nombre_ingresoT = $nombres_ingresoT[$i];
    $cantidad_ingresoT = $cantidades_ingresoT[$i];
    $id_ingreso = isset($ids_ingresos[$i]) ? $ids_ingresos[$i] : null;

    // Verificar si la persona de apoyo económico ya existe
    $sentencia = $conexion->prepare("SELECT id FROM ingresos WHERE trabajador_id = :trabajador_id AND id = :id");
    $sentencia->bindParam(':trabajador_id', $trabajador_id);
    $sentencia->bindParam(':id', $id_ingreso);
    $sentencia->execute();
    $ingresoT = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($ingresoT) {
        // Si la persona apoyada ya existe, actualizar
        $sentencia = $conexion->prepare("UPDATE ingresos SET nombre_persona = :nombre_persona, monto = :monto WHERE trabajador_id = :trabajador_id AND id = :id");
        $sentencia->bindParam(':id', $id_ingreso); //
    } else {
        // Si la persona apoyada no existe, insertar
        $sentencia = $conexion->prepare("INSERT INTO ingresos (trabajador_id, nombre_persona, monto) VALUES (:trabajador_id, :nombre_persona, :monto )");
    }
    

    // Vincular los valores de los campos a la consulta SQL
    $sentencia->bindParam(':trabajador_id', $trabajador_id);
    $sentencia->bindParam(':nombre_persona', $nombre_ingresoT);
    $sentencia->bindParam(':monto', $cantidad_ingresoT);
    $sentencia->execute();
}

$eliminar_ingresos = isset($_POST['eliminar_ingreso']) ? $_POST['eliminar_ingreso'] : [];

    // Procesar eliminaciones
if (!empty($eliminar_ingresos)) {
foreach ($eliminar_ingresos as $idIngreso) {
    $sentencia = $conexion->prepare("DELETE FROM ingresos WHERE id = :idingreso");
    $sentencia->bindParam(':idingreso', $idIngreso);
    $sentencia->execute();
 
}
}
//Egresos

$descripciones_egresoT = isset($_POST['descripcion_egreso']) ? $_POST['descripcion_egreso'] : [];
$cantidades_egresoT = isset($_POST['monto_egreso']) ? $_POST['monto_egreso'] : [];
$observaciones_egresoT = isset($_POST['observacion_egreso']) ? $_POST['observacion_egreso'] : [];
$ids_egresos = isset($_POST['id_egreso']) ? $_POST['id_egreso'] : null;
$num_egresos = count($descripciones_egresoT);

if ($num_egresos!== count($cantidades_egresoT) || $num_egresos !== count($observaciones_egresoT)) {
    echo "Error: Todos los campos deben tener la misma cantidad de entradas.";
    exit;
}

// Iterar sobre cada persona apoyada
for ($i = 0; $i < $num_egresos; $i++) {
    $descripcion_egresoT = $descripciones_egresoT[$i];
    $cantidad_egresoT = $cantidades_egresoT[$i];
    $observacion_egresoT = $observaciones_egresoT[$i];
    $id_egreso = isset($ids_egresos[$i]) ? $ids_egresos[$i] : null;


    // Verificar si la persona de apoyo económico ya existe
    $sentencia = $conexion->prepare("SELECT id FROM egresos WHERE trabajador_id = :trabajador_id AND id = :id");
    $sentencia->bindParam(':trabajador_id', $trabajador_id);
    $sentencia->bindParam(':id', $id_egreso);
    $sentencia->execute();
    $egresoT = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($egresoT) {
        // Si la persona apoyada ya existe, actualizar
        $sentencia = $conexion->prepare("UPDATE egresos SET descripcion = :descripcion_egreso, monto = :monto, observaciones = :observaciones  WHERE trabajador_id = :trabajador_id AND id = :id");
        $sentencia->bindParam(':id', $id_egreso); //
    } else {
        // Si la persona apoyada no existe, insertar
        $sentencia = $conexion->prepare("INSERT INTO egresos (trabajador_id, descripcion, monto, observaciones) VALUES (:trabajador_id, :descripcion_egreso, :monto, :observaciones )");
    }

    // Vincular los valores de los campos a la consulta SQL
    $sentencia->bindParam(':trabajador_id', $trabajador_id);
    $sentencia->bindParam(':descripcion_egreso', $descripcion_egresoT);
    $sentencia->bindParam(':monto', $cantidad_egresoT);
    $sentencia->bindParam(':observaciones', $observacion_egresoT);
    $sentencia->execute();
}

$eliminar_egresos = isset($_POST['eliminar_egreso']) ? $_POST['eliminar_egreso'] : [];

    // Procesar eliminaciones
if (!empty($eliminar_egresos)) {
foreach ($eliminar_egresos as $idEgreso) {
    $sentencia = $conexion->prepare("DELETE FROM egresos WHERE id = :idegreso");
    $sentencia->bindParam(':idegreso', $idEgreso);
    $sentencia->execute();
 
}
}
 //Habitalidad
 $tipo_vivienda = isset($_POST['tipo_vivienda']) ? $_POST['tipo_vivienda'] :"";
 $material_vivienda = isset($_POST['material_vivienda']) ? $_POST['material_vivienda'] :"";
 $n_habitaciones = isset($_POST['numero_habitaciones']) ? $_POST['numero_habitaciones'] :"";
 $n_banos = isset($_POST['numero_banos']) ? $_POST['numero_banos'] :"";
 $n_cocina = isset($_POST['cocina']) ? $_POST['cocina'] :"";
 $n_logia = isset($_POST['logia']) ? $_POST['logia'] :"";
 $condiciones_h = isset($_POST['condiciones_habitabilidad']) ? $_POST['condiciones_habitabilidad'] :"";


 $sentencia = $conexion->prepare("
 UPDATE condiciones_habitabilidad 
 SET tipo_vivienda = :tipo_vivienda,
     material_vivienda = :material_vivienda,
     num_habitaciones = :num_habitaciones,
     num_banos = :num_banos,
     num_cocina = :num_cocina,
     num_logia = :num_logia,
     condiciones_habitabilidad = :condiciones_habitabilidad
 WHERE trabajador_id = :trabajador_id
");

// Vincular los parámetros a la consulta SQ
$sentencia->bindParam(':trabajador_id', $trabajador_id);
$sentencia->bindParam(':tipo_vivienda', $tipo_vivienda);
$sentencia->bindParam(':material_vivienda', $material_vivienda);
$sentencia->bindParam(':num_habitaciones', $n_habitaciones);
$sentencia->bindParam(':num_banos', $n_banos);
$sentencia->bindParam(':num_cocina', $n_cocina);
$sentencia->bindParam(':num_logia', $n_logia);
$sentencia->bindParam(':condiciones_habitabilidad', $condiciones_h);
$sentencia->execute();

//Mapa conceptual
$mapa_conceptual = isset($_POST['mapa_conceptual']) ? $_POST['mapa_conceptual'] :"";
$sentencia = $conexion->prepare("SELECT id FROM mapa_conceptual WHERE trabajador_id = :trabajador_id");
$sentencia->bindParam(':trabajador_id', $trabajador_id);
$sentencia->execute();
$mapaExistente = $sentencia->fetch(PDO::FETCH_ASSOC);

if ($mapaExistente) {
    // Si existe, hacer un UPDATE
    $sentencia = $conexion->prepare("UPDATE mapa_conceptual SET mapa_conceptual	 = :mapa_conceptual	 WHERE trabajador_id = :trabajador_id");
} else {
    // Si no existe, hacer un INSERT
    $sentencia = $conexion->prepare("INSERT INTO mapa_conceptual (trabajador_id, mapa_conceptual) VALUES (:trabajador_id, :mapa_conceptual)");
}
$sentencia->bindParam(':trabajador_id', $trabajador_id);
$sentencia->bindParam(':mapa_conceptual', $mapa_conceptual);
$sentencia->execute();

//Otros 
$otros = isset($_POST['otros']) ? $_POST['otros'] :"";
$sentencia = $conexion->prepare("SELECT id FROM otros WHERE trabajador_id = :trabajador_id");
$sentencia->bindParam(':trabajador_id', $trabajador_id);
$sentencia->execute();
$otrosExistente = $sentencia->fetch(PDO::FETCH_ASSOC);
if ($otrosExistente) {
    // Si existe, hacer un UPDATE
    $sentencia = $conexion->prepare("UPDATE otros SET descripcion = :descripcion_otros	 WHERE trabajador_id = :trabajador_id");
} else {
    // Si no existe, hacer un INSERT
    $sentencia = $conexion->prepare("INSERT INTO otros (trabajador_id, descripcion) VALUES (:trabajador_id, :descripcion_otros)");
}
$sentencia->bindParam(':trabajador_id', $trabajador_id);
$sentencia->bindParam(':descripcion_otros', $otros);
$sentencia->execute();

//beneficios valora
$beneficioV = isset($_POST['beneficios_valora']) ? $_POST['beneficios_valora'] :"";
$sentencia = $conexion->prepare("SELECT id FROM beneficios_valorados WHERE trabajador_id = :trabajador_id");
$sentencia->bindParam(':trabajador_id', $trabajador_id);
$sentencia->execute();
$beneficioVExistente = $sentencia->fetch(PDO::FETCH_ASSOC);
if ($beneficioVExistente) {
    // Si existe, hacer un UPDATE
    $sentencia = $conexion->prepare("UPDATE beneficios_valorados SET beneficio = :beneficioV	WHERE trabajador_id = :trabajador_id");
} else {
    // Si no existe, hacer un INSERT
    $sentencia = $conexion->prepare("INSERT INTO beneficios_valorados (trabajador_id, beneficio) VALUES (:trabajador_id, :beneficioV)");
}
$sentencia->bindParam(':trabajador_id', $trabajador_id);
$sentencia->bindParam(':beneficioV', $beneficioV);
$sentencia->execute();


//beneficios necesarios
$beneficioN = isset($_POST['beneficios_necesarios']) ? $_POST['beneficios_necesarios'] :"";
$sentencia = $conexion->prepare("SELECT id FROM beneficios_necesarios WHERE trabajador_id = :trabajador_id");
$sentencia->bindParam(':trabajador_id', $trabajador_id);
$sentencia->execute();
$beneficioNExistente = $sentencia->fetch(PDO::FETCH_ASSOC);
if ($beneficioNExistente) {
    // Si existe, hacer un UPDATE
    $sentencia = $conexion->prepare("UPDATE beneficios_necesarios SET beneficio = :beneficioN	WHERE trabajador_id = :trabajador_id");
} else {
    // Si no existe, hacer un INSERT
    $sentencia = $conexion->prepare("INSERT INTO beneficios_necesarios (trabajador_id, beneficio) VALUES (:trabajador_id, :beneficioN)");
}
$sentencia->bindParam(':trabajador_id', $trabajador_id);
$sentencia->bindParam(':beneficioN', $beneficioN);
$sentencia->execute();


//Declaracion Salud 
$salud_cancer = isset($_POST['salud_cancer']) ? $_POST['salud_cancer'] : '';
$salud_sistema_nervioso = isset($_POST['salud_sistema_nervioso']) ? $_POST['salud_sistema_nervioso'] : '';
$salud_salud_mental = isset($_POST['salud_salud_mental']) ? $_POST['salud_salud_mental'] : '';
$salud_ojo = isset($_POST['salud_ojo']) ? $_POST['salud_ojo'] : '';
$salud_nariz_oidos = isset($_POST['salud_nariz_oidos']) ? $_POST['salud_nariz_oidos'] : '';
$salud_respiratorio = isset($_POST['salud_respiratorio']) ? $_POST['salud_respiratorio'] : '';
$salud_corazon = isset($_POST['salud_corazon']) ? $_POST['salud_corazon'] : '';
$salud_vascular = isset($_POST['salud_vascular']) ? $_POST['salud_vascular'] : '';
$salud_metabolico = isset($_POST['salud_metabolico']) ? $_POST['salud_metabolico'] : '';
$salud_digestivo = isset($_POST['salud_digestivo']) ? $_POST['salud_digestivo'] : '';
$salud_hepatitis_sida = isset($_POST['salud_hepatitis_sida']) ? $_POST['salud_hepatitis_sida'] : '';
$salud_renal = isset($_POST['salud_renal']) ? $_POST['salud_renal'] : '';
$salud_reproductor_femenino = isset($_POST['salud_reproductor_femenino']) ? $_POST['salud_reproductor_femenino'] : '';
$salud_autoinmune = isset($_POST['salud_autoinmune']) ? $_POST['salud_autoinmune'] : '';
$salud_tiroides = isset($_POST['salud_tiroides']) ? $_POST['salud_tiroides'] : '';
$salud_esqueletico = isset($_POST['salud_esqueletico']) ? $_POST['salud_esqueletico'] : '';
$salud_congenito = isset($_POST['salud_congenito']) ? $_POST['salud_congenito'] : '';
$salud_embarazo = isset($_POST['salud_embarazo']) ? $_POST['salud_embarazo'] : '';


$sentencia = $conexion->prepare("SELECT id FROM declaracion_salud WHERE trabajador_id = :trabajador_id");
$sentencia->bindParam(':trabajador_id', $trabajador_id);
$sentencia->execute();
$declaracionSaludExistente = $sentencia->fetch(PDO::FETCH_ASSOC);
print_r($declaracionSaludExistente);
if ($declaracionSaludExistente) {
    // Si existe, hacer un UPDATE
    $sentencia = $conexion->prepare("UPDATE declaracion_salud SET salud_cancer = :salud_cancer, salud_sistema_nervioso = :salud_sistema_nervioso, 
     salud_salud_mental = :salud_salud_mental, salud_ojo = :salud_ojo, salud_nariz_oidos = :salud_nariz_oidos, salud_respiratorio = :salud_respiratorio, 
     salud_corazon = :salud_corazon, salud_vascular = :salud_vascular, salud_metabolico = :salud_metabolico, salud_digestivo = :salud_digestivo,
     salud_hepatitis_sida = :salud_hepatitis_sida, salud_renal = :salud_renal, salud_reproductor_femenino = :salud_reproductor_femenino, 
     salud_autoinmune = :salud_autoinmune, salud_tiroides = :salud_tiroides, salud_esqueletico = :salud_esqueletico, salud_congenito = :salud_congenito,
     salud_embarazo = :salud_embarazo
    	WHERE trabajador_id = :trabajador_id");
} else {
    // Si no existe, hacer un INSERT
    $sentencia = $conexion->prepare("INSERT INTO declaracion_salud (trabajador_id, salud_cancer, salud_sistema_nervioso, salud_salud_mental,salud_ojo, salud_nariz_oidos, 
    salud_respiratorio, salud_corazon, salud_vascular, salud_metabolico,
     salud_digestivo, salud_hepatitis_sida, salud_renal, salud_reproductor_femenino, salud_autoinmune, salud_tiroides, salud_esqueletico, salud_congenito, salud_embarazo) 
     VALUES (:trabajador_id, :salud_cancer, :salud_sistema_nervioso, :salud_salud_mental, :salud_ojo, :salud_nariz_oidos, :salud_respiratorio, :salud_corazon, :salud_vascular,
      :salud_metabolico, :salud_digestivo, :salud_hepatitis_sida, :salud_renal, :salud_reproductor_femenino, :salud_autoinmune, :salud_tiroides, :salud_esqueletico,
       :salud_congenito, :salud_embarazo)");
}
$sentencia->bindParam(':trabajador_id', $trabajador_id);
$sentencia->bindParam(':salud_cancer', $salud_cancer);
$sentencia->bindParam(':salud_sistema_nervioso', $salud_sistema_nervioso);
$sentencia->bindParam(':salud_salud_mental', $salud_salud_mental);
$sentencia->bindParam(':salud_ojo', $salud_ojo);
$sentencia->bindParam(':salud_nariz_oidos', $salud_nariz_oidos);
$sentencia->bindParam(':salud_respiratorio', $salud_respiratorio);
$sentencia->bindParam(':salud_corazon', $salud_corazon);
$sentencia->bindParam(':salud_vascular', $salud_vascular);
$sentencia->bindParam(':salud_metabolico', $salud_metabolico);
$sentencia->bindParam(':salud_digestivo', $salud_digestivo);
$sentencia->bindParam(':salud_hepatitis_sida', $salud_hepatitis_sida);
$sentencia->bindParam(':salud_renal', $salud_renal);
$sentencia->bindParam(':salud_reproductor_femenino', $salud_reproductor_femenino);
$sentencia->bindParam(':salud_autoinmune', $salud_autoinmune);
$sentencia->bindParam(':salud_tiroides', $salud_tiroides);
$sentencia->bindParam(':salud_esqueletico', $salud_esqueletico);
$sentencia->bindParam(':salud_congenito', $salud_congenito);
$sentencia->bindParam(':salud_embarazo', $salud_embarazo);
$sentencia->execute();


//Detalles adicional de salud 
// Datos de salud recibidos desde el formulario
$nombres_persona = isset($_POST['nombre_persona']) ? $_POST['nombre_persona'] : [];
$enfermedades = isset($_POST['enfermedad']) ? $_POST['enfermedad'] : [];
$fechas_diagnostico = isset($_POST['fecha_diagnostico']) ? $_POST['fecha_diagnostico'] : [];
$estados_actuales = isset($_POST['estado_actual']) ? $_POST['estado_actual'] : [];
$ids_enfermedades = isset($_POST['id_enfermedad']) ? $_POST['id_enfermedad'] : [];
print_r($ids_enfermedades);
// Verificar que todos los arreglos tengan la misma longitud
$num_registros = count($nombres_persona);
if ($num_registros !== count($enfermedades) || 
    $num_registros !== count($fechas_diagnostico) || 
    $num_registros !== count($estados_actuales)) {
    echo "Error: Todos los campos deben tener la misma cantidad de entradas.";
    exit;
}

// Procesar cada fila del detalle de salud
for ($i = 0; $i < $num_registros; $i++) {
    $idEnfermedad = $ids_enfermedades[$i];

    if ($idEnfermedad === "new") {
        // Insertar nuevo registro de enfermedad
        $sentencia = $conexion->prepare("INSERT INTO personas_enfermas
            (trabajador_id,nombre_persona, enfermedad, fecha_diagnostico, estado_actual) 
            VALUES (:trabajador_id,:nombre_persona, :enfermedad, :fecha_diagnostico, :estado_actual)");
    } else {
        // Actualizar registro existente
        $sentencia = $conexion->prepare("UPDATE personas_enfermas SET 
            nombre_persona = :nombre_persona, 
            enfermedad = :enfermedad, 
            fecha_diagnostico = :fecha_diagnostico, 
            estado_actual = :estado_actual 
            WHERE trabajador_id = :trabajador_id AND id = :id_enfermedad");
        $sentencia->bindParam(':id_enfermedad', $idEnfermedad);
    }

    // Asignar valores a los parámetros
    $sentencia->bindParam(':trabajador_id', $trabajador_id);
    $sentencia->bindParam(':nombre_persona', $nombres_persona[$i]);
    $sentencia->bindParam(':enfermedad', $enfermedades[$i]);
    $sentencia->bindParam(':fecha_diagnostico', $fechas_diagnostico[$i]);
    $sentencia->bindParam(':estado_actual', $estados_actuales[$i]);

    // Ejecutar la sentencia
    $sentencia->execute();
}

// Procesar las eliminaciones de registros de enfermedades
$eliminar_enfermedad = isset($_POST['eliminar_enfermedad']) ? $_POST['eliminar_enfermedad'] : [];
if (!empty($eliminar_enfermedad)) {
    foreach ($eliminar_enfermedad as $idEnfermedad) {
        $sentencia = $conexion->prepare("DELETE FROM personas_enfermas WHERE id = :idEnfermedad");
        $sentencia->bindParam(':idEnfermedad', $idEnfermedad);
        $sentencia->execute();
    }
}


header('Location: index.php');
exit;


}
?>