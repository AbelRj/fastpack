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

    // Verificar que todos los arreglos tengan la misma longitud
    $num_familiares = count($nombres_apellido);
    if ($num_familiares !== count($parentescos) || $num_familiares !== count($fechas_nacimiento) || 
        $num_familiares !== count($sexos) || $num_familiares !== count($estados_civil) || $num_familiares !== count($niveles_educacionales) || $num_familiares !== count($actividades)) {
        echo "Error: Todos los campos deben tener la misma cantidad de entradas.";
        exit;
    }

    // Iterar sobre cada miembro del grupo familiar
    for ($i = 0; $i < $num_familiares; $i++) {
        $nombre_apellido = $nombres_apellido[$i];
        $parentesco = $parentescos[$i];
        $fecha_nacimiento = $fechas_nacimiento[$i];
        $sexo = $sexos[$i];
        $estado_civil = $estados_civil[$i];
        $nivel_educacional = $niveles_educacionales[$i];
        $actividad = $actividades[$i];

        // Verificar si el familiar ya existe
        $sentencia = $conexion->prepare("SELECT id FROM grupo_familiar WHERE trabajador_id = :trabajador_id AND nombre_apellido = :nombre_apellido");
        $sentencia->bindParam(':trabajador_id', $trabajador_id);
        $sentencia->bindParam(':nombre_apellido', $nombre_apellido);
        $sentencia->execute();
        $familiarExistente = $sentencia->fetch(PDO::FETCH_ASSOC);

        if ($familiarExistente) {
            // Si el familiar existe, actualizar
            $sentencia = $conexion->prepare("UPDATE grupo_familiar SET parentesco = :parentesco, fecha_nacimiento = :fecha_nacimiento, sexo = :sexo, estado_civil = :estado_civil, nivel_educacional = :nivel_educacional, actividad = :actividad WHERE trabajador_id = :trabajador_id AND nombre_apellido = :nombre_apellido");
        } else {
            // Si el familiar no existe, insertar
            $sentencia = $conexion->prepare("INSERT INTO grupo_familiar (trabajador_id, nombre_apellido, parentesco, fecha_nacimiento, sexo, estado_civil, nivel_educacional, actividad) VALUES (:trabajador_id, :nombre_apellido, :parentesco, :fecha_nacimiento, :sexo, :estado_civil, :nivel_educacional, :actividad)");
        }

        // Vincular los valores de los campos a la consulta SQL
        $sentencia->bindParam(':trabajador_id', $trabajador_id);
        $sentencia->bindParam(':nombre_apellido', $nombre_apellido);
        $sentencia->bindParam(':parentesco', $parentesco);
        $sentencia->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $sentencia->bindParam(':sexo', $sexo);
        $sentencia->bindParam(':estado_civil', $estado_civil);
        $sentencia->bindParam(':nivel_educacional', $nivel_educacional);
        $sentencia->bindParam(':actividad', $actividad);
        $sentencia->execute();
    }
         // Recuperar los IDs de los familiares a eliminar
         $eliminar_familiar = isset($_POST['eliminar_familiar']) ? $_POST['eliminar_familiar'] : [];
         print_r($eliminar_familiar);
             // Procesar eliminaciones
     if (!empty($eliminar_familiar)) {
         foreach ($eliminar_familiar as $idFamiliar) {
             $sentencia = $conexion->prepare("DELETE FROM grupo_familiar WHERE id = :idFamiliar");
             $sentencia->bindParam(':idFamiliar', $idFamiliar);
             $sentencia->execute();
          
         }
     }

     //Historia Familiar (en la actualidad)
     $historia = (isset($_POST['historia_familiar'])) ? $_POST['historia_familiar']:"";
    $sentencia = $conexion->prepare("UPDATE historia_familiar SET historia = :historia WHERE trabajador_id = :trabajador_id");
    $sentencia->bindParam(':trabajador_id', $trabajador_id);
    $sentencia->bindParam(':historia', $historia);
    $sentencia->execute();

    // Apoyo economico
    $nombres_apellidosAE = isset($_POST['a_quien_apoya']) ? $_POST['a_quien_apoya'] : [];
    $motivosAE = isset($_POST['motivo_apoyo']) ? $_POST['motivo_apoyo'] : [];
    $num_AETrabajador = count($nombres_apellidosAE);
    if ($num_AETrabajador!== count($motivosAE)) {
        echo "Error: Todos los campos deben tener la misma cantidad de entradas.";
        exit;
    }
    

// Iterar sobre cada persona apoyada
for ($i = 0; $i < $num_AETrabajador; $i++) {
    $nombre_apellidoAE = $nombres_apellidosAE[$i];
    $motivoAE = $motivosAE[$i];

    // Verificar si la persona de apoyo económico ya existe
    $sentencia = $conexion->prepare("SELECT id FROM apoyo_economico WHERE trabajador_id = :trabajador_id AND a_quien = :nombre_apellido");
    $sentencia->bindParam(':trabajador_id', $trabajador_id);
    $sentencia->bindParam(':nombre_apellido', $nombre_apellidoAE);
    $sentencia->execute();
    $persona_AE = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($persona_AE) {
        // Si la persona apoyada ya existe, actualizar
        $sentencia = $conexion->prepare("UPDATE apoyo_economico SET motivo = :motivo WHERE trabajador_id = :trabajador_id AND a_quien = :nombre_apellido");
    } else {
        // Si la persona apoyada no existe, insertar
        $sentencia = $conexion->prepare("INSERT INTO apoyo_economico (trabajador_id, a_quien, motivo) VALUES (:trabajador_id, :nombre_apellido, :motivo)");
    }

    // Vincular los valores de los campos a la consulta SQL
    $sentencia->bindParam(':trabajador_id', $trabajador_id);
    $sentencia->bindParam(':nombre_apellido', $nombre_apellidoAE);
    $sentencia->bindParam(':motivo', $motivoAE);
    $sentencia->execute();
}
    

    

    header('Location: index.php');
    exit;


}
?>