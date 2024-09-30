<?php include("templates/header.php") ?>

<canvas id="myChart" width="900" height="200"></canvas>

<script>
  var ctx = document.getElementById('myChart').getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'bar', // Tipo de gráfico: 'bar', 'line', 'pie', etc.
    data: {
        labels: ['Rojo', 'Azul', 'Amarillo', 'Verde', 'Púrpura', 'Naranja'],
        datasets: [{
            label: '# de votos',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
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






<?php include("templates/footer.php") ?>