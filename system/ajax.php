<?php
require 'session.php';
header('Content-Type: application/json');
$respuesta = json_encode(['success' => false, 'message' => 'Error al procesar la solicitud.']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    switch ($data['accion']) {

        //Reserva de libros
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
        
            //Mostrar lista de libros reservados
             case 'listar_reservas':
            if (isset($data['cliente_id'])) {
                $cliente_id = intval($data['cliente_id']);
                $reservas = [];
                $sql = "
                    SELECT r.id as reserva_id, l.nombre, l.autor, l.genero, r.fecha_devolucion
                    FROM reserva r
                    INNER JOIN libro l ON l.id = r.libro_id
                    WHERE r.cliente_id = $cliente_id AND r.estado = 1
                ";
                $q = mysqli_query($conn, $sql);
                while ($r = mysqli_fetch_assoc($q)) {
                    $reservas[] = $r;
                }
                $respuesta = json_encode(['success'=>true, 'prestamos'=>$reservas]);
            }
            break;

            //Devolver libros
            case 'devolver':
            if (isset($data['reserva_id'])) {
                $reserva_id = intval($data['reserva_id']);
                // Termina la reserva, estado=0 y la fecha de devolución real con NOW()
                $query = "UPDATE reserva SET estado=0, fecha_devuelto=NOW() WHERE id=$reserva_id";
                if (mysqli_query($conn, $query)) {
                    // Obtener el libro relacionado
                    $q = mysqli_query($conn, "SELECT libro_id FROM reserva WHERE id = $reserva_id");
                    $libro = mysqli_fetch_assoc($q);
                    if ($libro) {
                        $libro_id = intval($libro['libro_id']);
                        // Poner el libro como disponible
                        mysqli_query($conn, "UPDATE libro SET estado=1 WHERE id=$libro_id");
                        $respuesta = json_encode(['success' => true, 'message' => 'Libro devuelto correctamente.']);
                    }
                } else {
                    $respuesta = json_encode(['success' => false, 'message' => 'No se pudo actualizar la reserva.']);
                }
            }
            break;
            
            default:
            $respuesta = json_encode(['success' => false, 'message' => 'Acción no reconocida.']);
            break;
             
    }
}

echo $respuesta;
?>