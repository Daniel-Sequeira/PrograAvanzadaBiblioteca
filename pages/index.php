<?php
require '../system/session.php';
require '../layout/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
  <h1 class="h2">Dashboard</h1>
</div>


<div class="alert alert-info text-center" role="alert">
  Ultimos 15 días de reservaciones
</div>
<canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>

<h2>Libros Vencidos</h2>
<div class="table-responsive">
  <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th scope="col">Fecha Devolucion</th>
        <th scope="col">Libro</th>
        <th scope="col">Cliente</th>
        <th scope="col">Dias Vencidos</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $fecha_hoy = date('Y-m-d H:i:s');
      $query_libros_vencidos = "SELECT r.fecha_devolucion, l.nombre, c.nombre AS nombre_cliente FROM reserva r LEFT JOIN libro l ON r.libro_id = l.id LEFT JOIN cliente c ON r.cliente_id = c.id WHERE r.estado = 1 AND r.fecha_devolucion < '$fecha_hoy' ORDER BY r.fecha_devolucion ASC";
      $resultado= mysqli_query($conn, $query_libros_vencidos);
      foreach($resultado as $libro_vencido){
        echo "
        <tr>
          <td>{$libro_vencido['fecha_devolucion']}</td>
          <td>{$libro_vencido['nombre']}</td>
          <td>{$libro_vencido['nombre_cliente']}</td>
          <td>".floor((strtotime($fecha_hoy) - strtotime($libro_vencido['fecha_devolucion']))/(60*60*24))."</td>
        </tr>
        ";
      }
      ?>
    </tbody>
  </table>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Graphs
    const ctx = document.getElementById('myChart')
    // eslint-disable-next-line no-unused-vars
    const myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: [
          <?php
          for ($i = 15; $i >= 0; $i--) {
            if ($i > 0) {
              echo "'" . date('Y-m-d', strtotime("-$i days")) . "',";
            } else {
              echo "'" . date('Y-m-d') . "'";
            }
          }
          ?>
        ],
        datasets: [{
          data: [
            <?php
            $result = mysqli_query($conn, "SELECT COUNT(*) as count, DATE(fecha_reserva) as date FROM reserva WHERE fecha_reserva >= DATE_SUB(NOW(), INTERVAL 15 DAY) GROUP BY DATE(fecha_reserva)");
            $data = [];
            while ($row = mysqli_fetch_assoc($result)) {
              $data[$row['date']] = $row['count'];
            }

            for ($i = 15; $i >= 0; $i--) {
              if ($i > 0) {
                if (isset($data[date('Y-m-d', strtotime("-$i days"))])) {
                  echo $data[date('Y-m-d', strtotime("-$i days"))] . ',';
                } else {
                  echo '0,';
                }
              } else {
                if (isset($data[date('Y-m-d')])) {
                  echo $data[date('Y-m-d')];
                } else {
                  echo '0';
                }
              }
            }
            ?>
          ],
          lineTension: 0,
          backgroundColor: 'transparent',
          borderColor: '#007bff',
          borderWidth: 4,
          pointBackgroundColor: '#007bff'
        }]
      },
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: false
            }
          }]
        },
        legend: {
          display: false
        }
      }
    })
  });
</script>
<?php
require '../layout/footer.php';
