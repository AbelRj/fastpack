<?php

include("bd.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
    $sentencia = $conexion->prepare("SELECT nombre_apellido = :nombre_apellido FROM grupo_familiar");
    $sentencia->bindParam(":nombre_apellido", $nombreApellido);
    $sentencia->execute();
    $nombreApellidoBD = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    // Recuperar datos del formulario
    $trabajador_id = isset($_POST['id']) ? $_POST['id'] : null;
    
    // Usar el primer valor de trabajador_id si hay múltiples
    $nombres_apellido = isset($_POST['nombre_apellido_familiar']) ? $_POST['nombre_apellido_familiar'] : [];
    $parentescos = isset($_POST['parentesco']) ? $_POST['parentesco'] : [];
    $fechas_nacimiento = isset($_POST['fecha_nacimiento_familiar']) ? $_POST['fecha_nacimiento_familiar'] : [];
    $sexos = isset($_POST['sexo_familiar']) ? $_POST['sexo_familiar'] : [];
    $estados_civil = isset($_POST['estado_civil_familiar']) ? $_POST['estado_civil_familiar'] : [];
    $actividades = isset($_POST['actividad_familiar']) ? $_POST['actividad_familiar'] : [];
    
    // Verificar que todos los arreglos tengan la misma longitud
    $num_familiares = count($nombres_apellido);
    if ($num_familiares !== count($parentescos) || $num_familiares !== count($fechas_nacimiento) || 
        $num_familiares !== count($sexos) || $num_familiares !== count($estados_civil) || $num_familiares !== count($actividades)) {
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
        $actividad = $actividades[$i];

        // Verificar si el familiar ya existe
        $sentencia = $conexion->prepare("SELECT id FROM grupo_familiar WHERE trabajador_id = :trabajador_id AND nombre_apellido = :nombre_apellido");
        $sentencia->bindParam(':trabajador_id', $trabajador_id);
        $sentencia->bindParam(':nombre_apellido', $nombre_apellido);
        $sentencia->execute();
        $familiarExistente = $sentencia->fetch(PDO::FETCH_ASSOC);

        if ($familiarExistente) {
            // Si el familiar existe, actualizar
            $sentencia = $conexion->prepare("UPDATE grupo_familiar SET parentesco = :parentesco, fecha_nacimiento = :fecha_nacimiento, sexo = :sexo, estado_civil = :estado_civil, actividad = :actividad WHERE trabajador_id = :trabajador_id AND nombre_apellido = :nombre_apellido");
        } else {
            // Si el familiar no existe, insertar
            $sentencia = $conexion->prepare("INSERT INTO grupo_familiar (trabajador_id, nombre_apellido, parentesco, fecha_nacimiento, sexo, estado_civil, actividad) VALUES (:trabajador_id, :nombre_apellido, :parentesco, :fecha_nacimiento, :sexo, :estado_civil, :actividad)");
        }

        // Vincular los valores de los campos a la consulta SQL
        $sentencia->bindParam(':trabajador_id', $trabajador_id);
        $sentencia->bindParam(':nombre_apellido', $nombre_apellido);
        $sentencia->bindParam(':parentesco', $parentesco);
        $sentencia->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $sentencia->bindParam(':sexo', $sexo);
        $sentencia->bindParam(':estado_civil', $estado_civil);
        $sentencia->bindParam(':actividad', $actividad);
        $sentencia->execute();
    }

    header('Location: index.php');
    exit;
}


?>