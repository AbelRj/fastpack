<?php
include_once("bd.php"); // Asegúrate de que bd.php esté correctamente configurado

// URL API de Talana
$url = 'https://talana.com/es/api/persona/';

// Inicializar cURL
$ch = curl_init();

// Configurar cURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Autenticación
$headers = [
    'Authorization: token e2d3852b6e163af50cad031db86d919a7e64f706', // Token de acceso
    'Content-Type: application/json'
];
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Ejecutar la solicitud
$response = curl_exec($ch);

// Manejo de errores
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
    exit; // Salir en caso de error
}

curl_close($ch);

// Procesar la respuesta de la API
$datos = json_decode($response, true);

// Verifica si $datos no está vacío
if (!empty($datos)) {

    // Obtener todos los IDs existentes de la base de datos
   // $sql_ids = "SELECT id FROM [trabajador]";
    $sql_ids = "SELECT id FROM trabajador";
    $result_ids = $conexion->query($sql_ids);

    // Crear un array con los IDs existentes en la base de datos
    $ids_existentes = [];
    while ($fila = $result_ids->fetch(PDO::FETCH_ASSOC)) {
        $ids_existentes[] = $fila['id'];
    }

    // Preparar la consulta SQL para insertar solo los nuevos trabajadores
    $sql = "INSERT INTO [trabajador] (id, rut, nombre_apellido, sexo, fecha_nacimiento, 
            nacionalidad, profesion, domicilio, telefono, celular, correo_electronico, estado_civil)
            VALUES (:id, :rut, :nombre_apellido, :sexo, :fecha_nacimiento, :nacionalidad,
            :profesion, :domicilio, :telefono, :celular, :correo_electronico, :estado_civil)";

/*$sql = "INSERT INTO trabajador (id, rut, nombre_apellido, sexo, fecha_nacimiento, 
nacionalidad, profesion, domicilio, telefono, celular, correo_electronico, estado_civil)
VALUES (:id, :rut, :nombre_apellido, :sexo, :fecha_nacimiento, :nacionalidad,
:profesion, :domicilio, :telefono, :celular, :correo_electronico, :estado_civil)";*/

    $stmt = $conexion->prepare($sql);

    // Insertar solo los nuevos datos
    foreach ($datos as $personal) {
        // Si el ID ya existe en la base de datos, saltar la inserción
        if (in_array($personal['id'], $ids_existentes)) {
            continue; // Saltar a la siguiente iteración
        }

        // Asegúrate de que 'detalles' existe y tiene al menos un elemento
        $direccionCalle = isset($personal['detalles'][0]['direccionCalle']) ? $personal['detalles'][0]['direccionCalle'] : '';
        $direccionNumero = isset($personal['detalles'][0]['direccionNumero']) ? $personal['detalles'][0]['direccionNumero'] : '';
        $telefono = isset($personal['detalles'][0]['telefono']) ? $personal['detalles'][0]['telefono'] : '';
        $celular = isset($personal['detalles'][0]['celular']) ? $personal['detalles'][0]['celular'] : '';
        $estadoCivil = isset($personal['detalles'][0]['estadoCivil']) ? $personal['detalles'][0]['estadoCivil'] : '';
        $profesion = isset($personal['detalles'][0]['profesion']) ? $personal['detalles'][0]['profesion'] : '';
        $fechaNacimiento = isset($personal['fechaNacimiento']) ? $personal['fechaNacimiento'] : '';

        // Concatenar dirección
        $direccion = trim($direccionCalle . ' ' . $direccionNumero);

        // Concatenar nombre completo
        $nombreCompleto = trim($personal['nombre'] . ' ' . $personal['apellidoPaterno'] . ' ' . $personal['apellidoMaterno']);

        // Ejecutar la consulta para insertar los datos del trabajador nuevo
        $stmt->execute([
            ':id' => $personal['id'],
            ':rut' => $personal['rut'],
            ':nombre_apellido' => $nombreCompleto,
            ':sexo' => $personal['sexo'],
            ':fecha_nacimiento' => $fechaNacimiento,
            ':nacionalidad' => $personal['nacionalidad'],
            ':profesion' => $profesion,
            ':domicilio' => $direccion,
            ':telefono' => $telefono,
            ':celular' => $celular,
            ':correo_electronico' => $personal['email'],
            ':estado_civil' => $estadoCivil
        ]);
    }

    // Responder con los datos insertados o actualizados
    //echo json_encode($datos);
} else {
    echo "No se encontraron datos.";
}

header('Location: index.php');
exit;
?>
