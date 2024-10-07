<?php
// Incluye la conexión a la base de datos
include("bd.php");

// Prepara y ejecuta la consulta
$sentencia = $conexion->prepare("SELECT nacionalidad, COUNT(*) as total FROM [trabajador] GROUP BY nacionalidad");
$sentencia->execute();
$conteo_pais = $sentencia->fetchAll(PDO::FETCH_ASSOC);

// Consulta para agrupar estados civiles similares
$sentencia_estado_civil = $conexion->prepare("
    SELECT 
        CASE 
            WHEN estado_civil IN ('Casado', 'Casada') THEN 'Casado'
            WHEN estado_civil IN ('Viudo', 'Viuda') THEN 'Viudo'
            WHEN estado_civil IN ('Divorciado', 'Divorciada') THEN 'Divorciado'
            WHEN estado_civil IN ('Separado', 'Separada') THEN 'Separado'
            WHEN estado_civil IN ('Soltero', 'Soltera') THEN 'Soltero'
            ELSE estado_civil
        END AS estado_civil_normalizado,
        COUNT(*) as total
    FROM [trabajador]
    GROUP BY 
        CASE 
            WHEN estado_civil IN ('Casado', 'Casada') THEN 'Casado'
            WHEN estado_civil IN ('Viudo', 'Viuda') THEN 'Viudo'
            WHEN estado_civil IN ('Divorciado', 'Divorciada') THEN 'Divorciado'
            WHEN estado_civil IN ('Separado', 'Separada') THEN 'Separado'
            WHEN estado_civil IN ('Soltero', 'Soltera') THEN 'Soltero'
            ELSE estado_civil
        END
");
$sentencia_estado_civil->execute();
$conteo_estado_civil = $sentencia_estado_civil->fetchAll(PDO::FETCH_ASSOC);
// Convertir los datos a arrays separados para estado_civil y totales
$estados_civiles = [];
$totales_estado_civil = [];

foreach ($conteo_estado_civil as $row) {
    $estados_civiles[] = $row['estado_civil_normalizado']; // Etiqueta de estado civil normalizada
    $totales_estado_civil[] = $row['total'];   // Total por estado civil normalizado
}

// Consulta para contar hombres y mujeres
$sentencia_genero = $conexion->prepare("SELECT sexo, COUNT(*) as total FROM [trabajador] GROUP BY sexo");
$sentencia_genero->execute();
$conteo_genero = $sentencia_genero->fetchAll(PDO::FETCH_ASSOC);

// Convertir los datos a arrays separados para sexo y totales
$generos = [];
$totales_genero = [];

foreach ($conteo_genero as $row) {
    $generos[] = $row['sexo'] == 'M' ? 'Hombres' : 'Mujeres'; // Convertir 'M' a 'Hombres' y 'F' a 'Mujeres'
    $totales_genero[] = $row['total'];
}

// Convertir los datos a arrays separados para países y totales
$paises = [];
$totales = [];

foreach ($conteo_pais as $row) {
    $paises[] = $row['nacionalidad']; // Suponiendo que el campo de país es 'nacionalidad'
    $totales[] = $row['total'];
}

// Incluir la cabecera de la plantilla
include("templates/header.php");
?>

<canvas id="myChart" width="900" height="200"></canvas>
<canvas id="chartEstadoCivil" width="900" height="200"></canvas>
<canvas id="chartGenero" width="900" height="200"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Obtener los datos de PHP
  var paises = <?php echo json_encode($paises); ?>; // Datos de nacionalidades
  var totales = <?php echo json_encode($totales); ?>; // Datos de conteos

  var ctx = document.getElementById('myChart').getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'bar', // Tipo de gráfico
    data: {
        labels: paises, // Etiquetas del gráfico
        datasets: [{
            label: 'Número de Trabajadores por País',
            data: totales, // Datos del conteo
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
  });
</script>
<script>
  // Gráfico de estado civil (solteros, casados, viudos, etc.)
  var estadosCiviles = <?php echo json_encode($estados_civiles); ?>; // Etiquetas de estado civil
  var totalesEstadoCivil = <?php echo json_encode($totales_estado_civil); ?>; // Datos del conteo por estado civil

  var ctxEstadoCivil = document.getElementById('chartEstadoCivil').getContext('2d');
  var chartEstadoCivil = new Chart(ctxEstadoCivil, {
    type: 'bar', // Tipo de gráfico: barras
    data: {
        labels: estadosCiviles, // Etiquetas del gráfico (Soltero, Casado, Viudo, etc.)
        datasets: [{
            label: 'Número de Trabajadores por Estado Civil',
            data: totalesEstadoCivil, // Datos del conteo
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',  // Color para el primer estado civil
                'rgba(54, 162, 235, 0.2)',  // Color para el segundo estado civil
                'rgba(255, 206, 86, 0.2)',  // Color para el tercer estado civil
                'rgba(75, 192, 192, 0.2)',  // Color para el cuarto estado civil
                'rgba(153, 102, 255, 0.2)', // Color para el quinto estado civil
                // Puedes agregar más colores si tienes más estados civiles
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                // Colores correspondientes a los bordes
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
  });
</script>
<script>
  // Gráfico de género (hombres y mujeres)
  var generos = <?php echo json_encode($generos); ?>; // Etiquetas de género
  var totalesGenero = <?php echo json_encode($totales_genero); ?>; // Datos de género

  var ctxGenero = document.getElementById('chartGenero').getContext('2d');
  var chartGenero = new Chart(ctxGenero, {
    type: 'bar',
    data: {
        labels: generos, // Etiquetas de género
        datasets: [{
            label: 'Número de Trabajadores por Género',
            data: totalesGenero, // Datos del conteo
            backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)'], // Diferentes colores
            borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)'],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
  });
</script>
</main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
