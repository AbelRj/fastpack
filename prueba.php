<?php
// Apoyo economico
    $nombres_apellidosAE = isset($_POST['a_quien_apoya']) ? $_POST['a_quien_apoya'] : [];
    $motivosAE = isset($_POST['motivo_apoyo']) ? $_POST['motivo_apoyo'] : [];
    $ids_apoyoF = isset($_POST['id_apoyoF']) ? $_POST['id_apoyoF'] : null;
    $num_AETrabajador = count($nombres_apellidosAE);
    print_r($ids_apoyoF);
    if ($num_AETrabajador!== count($motivosAE)) {
        echo "Error: Todos los campos deben tener la misma cantidad de entradas.";
        exit;
    }
    

// Iterar sobre cada persona apoyada
for ($i = 0; $i < $num_AETrabajador; $i++) {
    $nombre_apellidoAE = $nombres_apellidosAE[$i];
    $motivoAE = $motivosAE[$i];
    $id_apoyoF = $ids_apoyoF[$i];

    // Verificar si la persona de apoyo económico ya existe
    $sentencia = $conexion->prepare("SELECT id FROM apoyo_economico WHERE trabajador_id = :trabajador_id AND id = :id");
    $sentencia->bindParam(':trabajador_id', $trabajador_id);
    $sentencia->bindParam(':id', $id_apoyoF);
    $sentencia->execute();
    $persona_AE = $sentencia->fetch(PDO::FETCH_ASSOC);

    if ($persona_AE) {
        // Si la persona apoyada ya existe, actualizar
        $sentencia = $conexion->prepare("UPDATE apoyo_economico SET  a_quien = :nombre_apoyoF, motivo = :motivo WHERE trabajador_id = :trabajador_id AND id = :id");
    } else {
        // Si la persona apoyada no existe, insertar
        $sentencia = $conexion->prepare("INSERT INTO apoyo_economico (trabajador_id, a_quien, motivo) VALUES (:trabajador_id, :nombre_apoyoF, :motivo)");
    }

    // Vincular los valores de los campos a la consulta SQL
    $sentencia->bindParam(':trabajador_id', $trabajador_id);
    $sentencia->bindParam(':id', $id_apoyoF);
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