<?php
require '../system/session.php';
require '../layout/header.php';
?>
<div class="mt-3">
    <div class="mb-3 col-12 col-md-6">
        <h5>Cliente:</h5>
        <select class="form-select" id="cliente" onchange="cargarLibrosPrestados()">
            <option value="0" disabled selected>Seleccione un Cliente</option>
            <?php
            $clientes = mysqli_query($conn, "SELECT id, nombre FROM cliente WHERE estado = 1 ORDER BY nombre ASC");
            foreach ($clientes as $cliente) {
                echo "<option value='{$cliente['id']}'>{$cliente['nombre']}</option>";
            }
            ?>
        </select>
    </div>

    <div id="librosPrestadosSection" style="display:none;">
        <h5>Libros prestados:</h5>
        <div class="table-responsive">
            <table id="tablaLibrosPrestados" class="display w-100">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Autor</th>
                        <th>Género</th>
                        <th>Reservado hasta</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tbodyLibrosPrestados">
                   
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>

    function cargarLibrosPrestados() {
        const clienteId = document.getElementById('cliente').value;
        if (clienteId == 0) {
            document.getElementById('librosPrestadosSection').style.display = "none";
            return;
        }
        fetch('../system/ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                accion: 'listar_reservas',
                cliente_id: clienteId
            })
        })
        .then(response => response.json())
        .then(data => {
            
            let tbody = document.getElementById('tbodyLibrosPrestados');
            tbody.innerHTML = '';
            if (data.success && data.prestamos.length > 0) {
                data.prestamos.forEach(p => {
                    tbody.innerHTML += `<tr id="reserva-${p.reserva_id}">
                        <td>${p.nombre}</td>
                        <td>${p.autor}</td>
                        <td>${p.genero}</td>
                        <td>${p.fecha_devolucion}</td>
                        <td>
                            <button class="btn btn-sm btn-danger" onclick="devolverLibro(${p.reserva_id})">Devolver</button>
                        </td>
                    </tr>`;
                });
                document.getElementById('librosPrestadosSection').style.display = "";
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">No tiene libros prestados.</td></tr>';
                document.getElementById('librosPrestadosSection').style.display = "";
            }
        });
    }

    function devolverLibro(reservaId) {
        Swal.fire({
            title: 'Confirmar devolución',
            text: "¿Está seguro de devolver este libro?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, devolver',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('../system/ajax.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        accion: "devolver",
                        reserva_id: reservaId
                    })
                })
                .then(response => response.json())
                .then(data => {
                   
                    if (data.success) {
                        document.getElementById('reserva-' + reservaId).remove();
                        Swal.fire("Devuelto!", data.message, "success");
                    } else {
                        Swal.fire("Error!", data.message, "error");
                    }
                });
            }
        });
}
</script>
<?php
require '../layout/footer.php';
?>

