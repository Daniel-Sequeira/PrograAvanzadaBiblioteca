<?php
require '../system/session.php';
require '../layout/header.php';
?>
<div class="mt-3">
    <div class="mb-3 col-12 col-md-6">
        <h5>Cliente:</h5>
        <select class="form-select" id="cliente">
            <option value="0" disabled selected>Seleccione un Cliente</option>
            <?php
            $clientes = mysqli_query($conn, "SELECT id, nombre FROM cliente WHERE estado = 1 ORDER BY nombre ASC");
            foreach ($clientes as $cliente) {
                echo "<option value='{$cliente['id']}'>{$cliente['nombre']}</option>";
            }
            ?>
        </select>
    </div>

    <div>
        <h5>Libros disponibles para reservar:</h5>
        <div class="table-responsive">
            <table id="tablaLibros" class="display w-100">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Autor</th>
                        <th>Género</th>
                        <th>Año</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $resultado = mysqli_query($conn, "SELECT id, nombre, autor, genero, anno FROM libro WHERE estado = 1");
                    while ($row = mysqli_fetch_assoc($resultado)) {
                        echo "<tr id='libro-{$row['id']}'>
                                <td>{$row['nombre']}</td>
                                <td>{$row['autor']}</td>
                                <td>{$row['genero']}</td>
                                <td>{$row['anno']}</td>
                                <td class='text-center'>
                                    <button class='btn btn-sm btn-success' onclick='mostrarFechaReservacion({$row['id']})'>Reservar <span data-feather=\"user-check\" class=\"align-text-bottom\"></span></button>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalReservar" tabindex="-1" aria-labelledby="modalReservarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modalReservarLabel">Seleccione la fecha de devolución</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form onsubmit="confirmarReservacion(); return false;">
                    <input type="hidden" id="libroAReservar" value="0">
                    <div class="mb-3">
                        <label for="fechaDevolcion" class="form-label">Fecha</label>
                        <input type="date" class="form-control" id="fechaDevolcion" min="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d', (time() + (86400 * 30))) ?>" required>
                    </div>
                    <div class="mb-3 text-center">
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new DataTable('#tablaLibros', {
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.0.7/i18n/es-ES.json'
            }
        });
    });

    function mostrarFechaReservacion(id) {
        const clienteId = document.getElementById('cliente').value;
        if (clienteId == 0) {
            alert('Por favor, seleccione un cliente.');
            return;
        }
        const modal = new bootstrap.Modal(document.getElementById('modalReservar'));
        modal.show();
        document.getElementById('libroAReservar').value = id;
    }

    function confirmarReservacion() {
        const libroId = document.getElementById('libroAReservar').value;
        const clienteId = document.getElementById('cliente').value;
        const fechaDevolucion = document.getElementById('fechaDevolcion').value;

        if (libroId == 0 || clienteId == 0 || !fechaDevolucion) {
            alert('Por favor, complete todos los campos.');
            return;
        }

        fetch('../system/ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    accion: 'reservar',
                    libro_id: libroId,
                    cliente_id: clienteId,
                    fecha_devolucion: fechaDevolucion
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const fila = document.getElementById('libro-' + libroId);
                    fila.parentNode.removeChild(fila);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalReservar'));
                    modal.hide();
                    Swal.fire({
                        title: "Exito!",
                        text: data.message,
                        icon: "success"
                    });
                } else {
                    Swal.fire({
                        title: "Error!",
                        text: data.message,
                        icon: "error"
                    });
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>
<?php
require '../layout/footer.php';
?>