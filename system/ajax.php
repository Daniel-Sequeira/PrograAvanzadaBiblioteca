<?php
require 'session.php';
header('Content-Type: application/json');
$respuesta = json_encode(['success' => false, 'message' => 'Error al procesar la solicitud.']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    switch ($data['accion']) {
        case 'reservar':
            if (isset($data['libro_id']) && isset($data['cliente_id']) && isset($data['fecha_devolucion'])) {
                $libro_id = $data['libro_id'];
                $cliente_id = $data['cliente_id'];
                $fecha_devolucion = $data['fecha_devolucion'];
                $fecha_hoy = date('Y-m-d H:i:s');
                $query_crear_reserva = "INSERT INTO reserva (libro_id, cliente_id, fecha_reserva, fecha_devolucion, estado) VALUES ('$libro_id', '$cliente_id', '$fecha_hoy', '$fecha_devolucion', 1)";
                $result = mysqli_query($conn, $query_crear_reserva);
                if ($result) {
                    $query_actualizar_libro = "UPDATE libro SET estado = '2' WHERE id = '$libro_id'";
                    if(mysqli_query($conn, $query_actualizar_libro)){
                        $respuesta = json_encode(['success' => true, 'message' => 'Reserva realizada con éxito.']);
                    }
                }
            }
            break;
        default:
            $respuesta = json_encode(['success' => false, 'message' => 'Acción no reconocida.']);
            break;
    }
}

echo $respuesta;
