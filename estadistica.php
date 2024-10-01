<?php
// Incluye la conexión a la base de datos
include("bd.php");

// Prepara y ejecuta la consulta
$sentencia = $conexion->prepare("SELECT nacionalidad, COUNT(*) as total FROM trabajador GROUP BY nacionalidad");
$sentencia->execute();
$conteo_pais = $sentencia->fetchAll(PDO::FETCH_ASSOC);

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

<canvas id="lineChart" width="900" height="200"></canvas>
<script>
  var ctx = document.getElementById('lineChart').getContext('2d');
  var lineChart = new Chart(ctx, {
    type: 'line', // Tipo de gráfico
    data: {
        labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
        datasets: [{
            label: 'Ventas en USD',
            data: [120, 190, 300, 500, 200, 300],
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

<?php include("templates/footer.php"); ?>
