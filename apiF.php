<?php
// Conexión a la base de datos (ajusta estos datos según tu configuración)
$host = 'localhost'; // Cambia si es necesario
$dbname = 'tu_base_de_datos'; // Nombre de tu base de datos
$username = 'tu_usuario'; // Usuario de la base de datos
$password = 'tu_contraseña'; // Contraseña de la base de datos

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// URL API de Talana
$url = 'https://talana.com/es/api/persona/';

// Función para obtener los datos de la API
function obtenerDatos() {
    global $url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = [
        'Authorization: token e2d3852b6e163af50cad031db86d919a7e64f706',
        'Content-Type: application/json'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// Llamar a la función para obtener datos
$datos = obtenerDatos();

// Preparar la consulta SQL para insertar datos
$sql = "INSERT INTO trabajadores (id, rut, nombre, apellidoPaterno, apellidoMaterno, sexo, fechaNacimiento, 
nacionalidad, email) 
        VALUES (:id, :rut, :nombre, :apellidoPaterno, :apellidoMaterno, :sexo, :fechaNacimiento, 
        :nacionalidad, :email)
        ON DUPLICATE KEY UPDATE
            rut = VALUES(rut),
            nombre = VALUES(nombre),
            apellidoPaterno = VALUES(apellidoPaterno),
            apellidoMaterno = VALUES(apellidoMaterno),
            sexo = VALUES(sexo),
            fechaNacimiento = VALUES(fechaNacimiento),
            nacionalidad = VALUES(nacionalidad),
            email = VALUES(email)";

$stmt = $pdo->prepare($sql);

// Insertar datos en la base de datos
foreach ($datos as $personal) {
    $stmt->execute([
        ':id' => $personal['id'],
        ':rut' => $personal['rut'],
        ':nombre' => $personal['nombre'],
        ':apellidoPaterno' => $personal['apellidoPaterno'],
        ':apellidoMaterno' => $personal['apellidoMaterno'],
        ':sexo' => $personal['sexo'],
        ':fechaNacimiento' => $personal['fechaNacimiento'],
        ':nacionalidad' => $personal['nacionalidad'],
        ':email' => $personal['email']
    ]);
}

// Responder con los datos insertados o actualizados
echo json_encode($datos);
?>
