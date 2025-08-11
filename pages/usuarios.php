<?php
require '../system/session.php';
require '../layout/header.php';
$mensaje = [];
if (isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'agregar':
            $cedula = mysqli_real_escape_string($conn, $_POST['cedula']);
            $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
            $correo = mysqli_real_escape_string($conn, $_POST['correo']);
            $contra = isset($_POST['contra']) ? $_POST['contra'] : '';
            $contra_hash = hash('sha256', $contra);
            $sql = "INSERT INTO usuario (nombre, correo, contra, cedula, fecha_creacion, estado) VALUES ('$nombre', '$correo', '$contra_hash', '$cedula', current_timestamp(), 1)";
            if (mysqli_query($conn, $sql)) {
                $mensaje['mensaje'] = "Usuario agregado correctamente.";
                $mensaje['tipo'] = 'success';
            } else {
                $mensaje['mensaje'] = "Error al agregar el usuario";
                $mensaje['tipo'] = 'danger';
            }
            break;

        case 'editar':
            $id_usuario = mysqli_real_escape_string($conn, $_POST['id_usuario']);
            if(is_numeric($id_usuario) && $id_usuario > 0 && $id_usuario == (int)$id_usuario) {
                $cedula = mysqli_real_escape_string($conn, $_POST['cedula']);
                $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
                $correo = mysqli_real_escape_string($conn, $_POST['correo']);
                $contra = isset($_POST['contra']) ? $_POST['contra'] : '';
                $setContra = '';
                if ($contra !== '') {
                    $contra_hash = hash('sha256', $contra);
                    $setContra = ", contra='$contra_hash'";
                }
                $sql = "UPDATE usuario SET nombre='$nombre', correo='$correo', cedula='$cedula' $setContra WHERE id='$id_usuario'";
                if (mysqli_query($conn, $sql)) {
                    $mensaje['mensaje'] = "Usuario actualizado correctamente.";
                    if($setContra !== '') {
                        $mensaje['mensaje'] .= "<br>La contraseña ha sido actualizada.";
                    }
                    $mensaje['tipo'] = 'success';
                } else {
                    $mensaje['mensaje'] = "Error al actualizar el usuario";
                    $mensaje['tipo'] = 'danger';
                }
                break;
            }else{
                $mensaje['mensaje'] = "ERROR.";
                $mensaje['tipo'] = 'danger';
            }

        case 'eliminar':
            $id_usuario = mysqli_real_escape_string($conn, $_POST['id_usuario']);
            if(is_numeric($id_usuario) && $id_usuario > 0 && $id_usuario == (int)$id_usuario) {
                $sql = "UPDATE usuario SET estado = 0 WHERE id='$id_usuario'";
                if (mysqli_query($conn, $sql)) {
                    $mensaje['mensaje'] = "Usuario eliminado correctamente.";
                    $mensaje['tipo'] = 'success';
                } else {
                    $mensaje['mensaje'] = "Error al eliminar el usuario";
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
    <h1 class="h2">Mantenimientos de Usuarios</h1>
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
    <table id="tablaUsuarios" class="table table-striped table-sm w-100">
        <thead>
            <tr>
                <th scope="col">Cedula</th>
                <th scope="col">Nombre</th>
                <th scope="col">Correo</th>
                <th scope="col">Fecha de Creación</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $usuarios = mysqli_query($conn, "SELECT * FROM usuario WHERE estado != 0 ORDER BY nombre ASC");
            if(mysqli_num_rows($usuarios)==0){
                echo "<tr><td colspan='6' class='text-center'>No hay usuarios registrados.</td></tr>";
            }else{
                foreach ($usuarios as $usuario) {
                    $estado_color = $usuario['estado'] == 1 ? 'success' : 'danger';
                    $estado_nombre = $usuario['estado'] == 1 ? '<span data-feather="check-circle" class="align-text-bottom"></span>'
                        : '<span data-feather="clock" class="align-text-bottom"></span>';
                    echo "<tr id=\"usuario-{$usuario['id']}\">
                                        <td>{$usuario['cedula']}</td>
                                        <td>{$usuario['nombre']}</td>
                                        <td>{$usuario['correo']}</td>
                                        <td>{$usuario['fecha_creacion']}</td>
                                        <td><span class=\"badge text-bg-$estado_color\">$estado_nombre</span></td>
                                        <td>
                                            <button onclick='editarUsuario({$usuario['id']})' class='btn btn-primary'><span data-feather=\"edit\" class=\"align-text-bottom\"></span></button>
                                            <button onclick='eliminarUsuario({$usuario['id']})' class='btn btn-danger'><span data-feather=\"trash\" class=\"align-text-bottom\"></span></button>
                                        </td>
                                    </tr>";
                }
            }
            ?>
        </tbody>
    </table>

    <!-- Modal de agregar usuario -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addModalLabel">Nuevo Usuario</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cedula" class="form-label">Cedula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" required placeholder="Cedula del usuario" oninput="checkCedula(this.value, 'nombre')">
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Nombre del usuario">
                        </div>
                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correo" name="correo" required placeholder="Correo electrónico">
                        </div>
                        <div class="mb-3">
                            <label for="contra" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="contra" name="contra" required placeholder="Contraseña">
                        </div>
                        <div class="text-center">
                            <button type="submit" name="accion" value="agregar" class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de editar usuario -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Editar Usuario #<span id="spanNumUsuario"></span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="">
                    <input type="hidden" name="id_usuario" id="id_usuario">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="cedulaEdit" class="form-label">Cedula</label>
                            <input type="text" class="form-control" id="cedulaEdit" name="cedula" required placeholder="Cedula del usuario" oninput="checkCedula(this.value, 'nombreEdit')">
                        </div>
                        <div class="mb-3">
                            <label for="nombreEdit" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombreEdit" name="nombre" required placeholder="Nombre del usuario">
                        </div>
                        <div class="mb-3">
                            <label for="correoEdit" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correoEdit" name="correo" required placeholder="Correo electrónico">
                        </div>
                        <div class="mb-3">
                            <label for="contraEdit" class="form-label">Contraseña (dejar en blanco para no cambiar)</label>
                            <input type="password" class="form-control" id="contraEdit" name="contra" placeholder="Nueva contraseña">
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
        new DataTable('#tablaUsuarios', {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.0.7/i18n/es-ES.json'
            }
        });
    });



        function editarUsuario(id_usuario) {
            var editModal = new bootstrap.Modal(document.getElementById('editModal'));
            editModal.show();
            document.getElementById('id_usuario').value = id_usuario;
            document.getElementById('spanNumUsuario').innerText = id_usuario;
            const fila = document.getElementById('usuario-' + id_usuario);
            const cedula = fila.children[0].innerText;
            const nombre = fila.children[1].innerText;
            const correo = fila.children[2].innerText;
            document.getElementById('cedulaEdit').value = cedula;
            document.getElementById('nombreEdit').value = nombre;
            document.getElementById('correoEdit').value = correo;
            document.getElementById('contraEdit').value = '';
        }

        function eliminarUsuario(id_usuario) {
            if (confirm("¿Estás seguro de eliminar el usuario #" + id_usuario + "?")) {
                const form = document.createElement('form');
                form.method = 'post';
                form.action = '';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'id_usuario';
                input.value = id_usuario;
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
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }  
        }
    </script>
<?php
require '../layout/footer.php';
