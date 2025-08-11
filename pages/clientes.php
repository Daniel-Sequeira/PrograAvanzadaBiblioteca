<?php
require '../system/session.php';
require '../layout/header.php';
$mensaje = [];
if (isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'agregar':
            $cedula = mysqli_real_escape_string($conn, $_POST['cedula']);
            $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
            $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
            $correo = mysqli_real_escape_string($conn, $_POST['correo']);
            $sql = "INSERT INTO cliente (cedula, nombre, telefono, correo, fecha_creacion, estado) VALUES ('$cedula', '$nombre', '$telefono', '$correo', current_timestamp(), 1)";
            if (mysqli_query($conn, $sql)) {
                $mensaje['mensaje'] = "Cliente agregado correctamente.";
                $mensaje['tipo'] = 'success';
            } else {
                $mensaje['mensaje'] = "Error al agregar el cliente";
                $mensaje['tipo'] = 'danger';
            }
            break;

        case 'editar':
            $id_cliente = mysqli_real_escape_string($conn, $_POST['id_cliente']);
            if(is_numeric($id_cliente) && $id_cliente > 0 && $id_cliente == (int)$id_cliente) {
                $cedula = mysqli_real_escape_string($conn, $_POST['cedula']);
                $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
                $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
                $correo = mysqli_real_escape_string($conn, $_POST['correo']);
                $sql = "UPDATE cliente SET cedula='$cedula', nombre='$nombre', telefono='$telefono', correo='$correo' WHERE id='$id_cliente'";
                if (mysqli_query($conn, $sql)) {
                    $mensaje['mensaje'] = "Cliente actualizado correctamente.";
                    $mensaje['tipo'] = 'success';
                } else {
                    $mensaje['mensaje'] = "Error al actualizar el cliente";
                    $mensaje['tipo'] = 'danger';
                }
                break;
            }else{
                $mensaje['mensaje'] = "ERROR.";
                $mensaje['tipo'] = 'danger';
            }

        case 'eliminar':
            $id_cliente = mysqli_real_escape_string($conn, $_POST['id_cliente']);
            if(is_numeric($id_cliente) && $id_cliente > 0 && $id_cliente == (int)$id_cliente) {
                $sql = "UPDATE cliente SET estado = 0 WHERE id='$id_cliente'";
                if (mysqli_query($conn, $sql)) {
                    $mensaje['mensaje'] = "Cliente eliminado correctamente.";
                    $mensaje['tipo'] = 'success';
                } else {
                    $mensaje['mensaje'] = "Error al eliminar el cliente";
                    $mensaje['tipo'] = 'danger';
                }
            }else{
                $mensaje['mensaje'] = "ERROR.";
                $mensaje['tipo'] = 'danger';
            }
            break;
    }
}
?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Mantenimientos de Clientes</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModal">
            Agregar
        </button>
    </div>
</div>
<?php
if (!empty($mensaje)) {
?>
    <div class="alert alert-<?= $mensaje['tipo'] ?> alert-dismissible fade show" role="alert">
        <strong><?= $mensaje['mensaje'] ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php
}
?>
<div class="table-responsive">
    <table id="tablaClientes" class="table table-striped table-sm w-100">
        <thead>
            <tr>
                <th scope="col">Cedula</th>
                <th scope="col">Nombre</th>
                <th scope="col">Teléfono</th>
                <th scope="col">Correo</th>
                <th scope="col">Fecha de Creación</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $clientes = mysqli_query($conn, "SELECT * FROM cliente WHERE estado != 0 ORDER BY nombre ASC");
            if(mysqli_num_rows($clientes)==0){
                echo "<tr><td colspan='7' class='text-center'>No hay clientes registrados.</td></tr>";
            }else{
                foreach ($clientes as $cliente) {
                    $estado_color = $cliente['estado'] == 1 ? 'success' : 'danger';
                    $estado_nombre = $cliente['estado'] == 1 ? '<span data-feather="check-circle" class="align-text-bottom"></span>'
                        : '<span data-feather="clock" class="align-text-bottom"></span>';
                    echo "<tr id=\"cliente-{$cliente['id']}\">
                                        <td>{$cliente['cedula']}</td>
                                        <td>{$cliente['nombre']}</td>
                                        <td>{$cliente['telefono']}</td>
                                        <td>{$cliente['correo']}</td>
                                        <td>{$cliente['fecha_creacion']}</td>
                                        <td><span class=\"badge text-bg-$estado_color\">$estado_nombre</span></td>
                                        <td>
                                            <button onclick='editarCliente({$cliente['id']})' class='btn btn-primary'><span data-feather=\"edit\" class=\"align-text-bottom\"></span></button>
                                            <button onclick='eliminarCliente({$cliente['id']})' class='btn btn-danger'><span data-feather=\"trash\" class=\"align-text-bottom\"></span></button>
                                        </td>
                                    </tr>";
                }
            }
            ?>
        </tbody>
    </table>

    <!-- Modal de agregar cliente -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addModalLabel">Nuevo Cliente</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cedula" class="form-label">Cedula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" required placeholder="Cedula del cliente" oninput="checkCedula(this.value, 'nombre')">
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Nombre del cliente">
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required placeholder="Teléfono">
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correo" name="correo" required placeholder="Correo electrónico">
                        </div>
                        <div class="text-center">
                            <button type="submit" name="accion" value="agregar" class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de editar cliente -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Editar Cliente #<span id="spanNumCliente"></span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="">
                    <input type="hidden" name="id_cliente" id="id_cliente">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cedulaEdit" class="form-label">Cedula</label>
                            <input type="text" class="form-control" id="cedulaEdit" name="cedula" required placeholder="Cedula del cliente" oninput="checkCedula(this.value, 'nombreEdit')">
                        </div>
                        <div class="mb-3">
                            <label for="nombreEdit" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombreEdit" name="nombre" required placeholder="Nombre del cliente">
                        </div>
                        <div class="mb-3">
                            <label for="telefonoEdit" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" id="telefonoEdit" name="telefono" required placeholder="Teléfono">
                        </div>
                        <div class="mb-3">
                            <label for="correoEdit" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correoEdit" name="correo" required placeholder="Correo electrónico">
                        </div>
                        <div class="text-center">
                            <button type="submit" name="accion" value="editar" class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery para DataTables -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables core -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>

<!-- DataTables con Bootstrap 5 -->
<script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.min.js"></script>

    <script>

        document.addEventListener('DOMContentLoaded', function() {
        new DataTable('#tablaClientes', {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.0.7/i18n/es-ES.json'
            }
        });
    });


        function editarCliente(id_cliente) {
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
            document.getElementById('id_cliente').value = id_cliente;
            document.getElementById('spanNumCliente').innerText = id_cliente;
            const fila = document.getElementById('cliente-' + id_cliente);
            const cedula = fila.children[0].innerText;
            const nombre = fila.children[1].innerText;
            const telefono = fila.children[2].innerText;
            const correo = fila.children[3].innerText;
            document.getElementById('cedulaEdit').value = cedula;
            document.getElementById('nombreEdit').value = nombre;
            document.getElementById('telefonoEdit').value = telefono;
            document.getElementById('correoEdit').value = correo;
        }

        function eliminarCliente(id_cliente) {
            if (confirm("¿Estás seguro de eliminar el cliente #" + id_cliente + "?")) {
                const form = document.createElement('form');
                form.method = 'post';
                form.action = '';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'id_cliente';
                input.value = id_cliente;
                form.appendChild(input);
                const accionInput = document.createElement('input');
                accionInput.type = 'hidden';
                accionInput.name = 'accion';
                accionInput.value = 'eliminar';
                form.appendChild(accionInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function checkCedula(cedulaInput, inputIdNombre){
            const regex = /^[0-9]{9}$/;
            if(regex.test(cedulaInput)){
                fetch(`https://api.hacienda.go.cr/fe/ae?identificacion=${cedulaInput}`)
                    .then(response => response.json())
                    .then(data => {
                        if(data.nombre && data.nombre.length > 0) {
                            const inputNombre=document.getElementById(inputIdNombre);
                            if(inputNombre.value !== '' && inputNombre.value !== data.nombre) {
                                confirm(`La cédula ${cedulaInput} corresponde a ${data.nombre}. ¿Desea actualizar el nombre?`) ? inputNombre.value = data.nombre : null;
                            }else if(inputNombre.value === '') {
                                inputNombre.value = data.nombre;
                            }
                        } else {
                            error.log('No se encontró información para la cédula proporcionada.');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }  
        }
    </script>
<?php
require '../layout/footer.php';
