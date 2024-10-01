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
        // Asegúrate de que 'detalles' existe y tiene al menos un elemento
        $direccionCalle = isset($personal['detalles'][0]['direccionCalle']) ? $personal['detalles'][0]['direccionCalle'] : '';
        $direccionNumero = isset($personal['detalles'][0]['direccionNumero']) ? $personal['detalles'][0]['direccionNumero'] : '';
        $telefono = isset($personal['detalles'][0]['telefono']) ? $personal['detalles'][0]['telefono'] : '';
        $celular = isset($personal['detalles'][0]['celular']) ? $personal['detalles'][0]['celular'] : '';
        $estadoCivil = isset($personal['detalles'][0]['estadoCivil']) ? $personal['detalles'][0]['estadoCivil'] : '';

        // Concatenar dirección
        $direccion = trim($direccionCalle . ' ' . $direccionNumero);

        // Concatenar nombre completo
        $nombreCompleto = trim($personal['nombre'] . ' ' . $personal['apellidoPaterno'] . ' ' . $personal['apellidoMaterno']);

        // Muestra solo los campos específicos
        $output = [
            'id' => $personal['id'],
            'rut' => $personal['rut'],
            'nombreCompleto' => $nombreCompleto, // Nombre completo concatenado
            'sexo' => $personal['sexo'],
            'fechaNacimiento' => $personal['fechaNacimiento'],
            'nacionalidad' => $personal['nacionalidad'],
            'profesion' => $personal['profesion']
            'direccion' => $direccion // Dirección concatenada
            'telefono' => $telefono,
            'celular' => $celular,
            'email' => $personal['email'],
            'estadoCivil' => $estadoCivil
    



        ];

        echo '<pre>';
        print_r($output);
        echo '</pre>';
    }
} else {
    echo "No se encontraron datos.";
}
?>
