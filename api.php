<?php
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

// Ejecuta la solicitud
$response = curl_exec($ch);

// Manejo de errores
if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
}

curl_close($ch);

// Procesar la respuesta
$datos = json_decode($response, true);    

// Verifica si $datos no está vacío
if (!empty($datos)) {
    foreach ($datos as $personal) {
        // Muestra la información de cada personal
        echo '<pre>';
        print_r($personal);
        echo '</pre>';
    

    }
} else {
    echo "No se encontraron datos.";
}
?>