<?php
require '../system/session.php';
require '../layout/header.php';
$mensaje = [];
if (isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'agregar':
            $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
            $correo = mysqli_real_escape_string($conn, $_POST['correo']);
            // Si se ingresó una contraseña, se hashea
            if (!empty($_POST['contra'])) {
                $contra = hash('sha256', $_POST['contra']);
            } 
            $cedula = mysqli_real_escape_string($conn, $_POST['cedula']);
            $sql = "INSERT INTO usuario (nombre, correo, contra, cedula) VALUES ('$nombre', '$correo', '$contra', '$cedula')";
            if (mysqli_query($conn, $sql)) {
                $mensaje['mensaje'] = "Usuario agregado correctamente.";
                $mensaje['tipo'] = 'success';
            } else {
                $mensaje['mensaje'] = "Error al agregar el usuario";
                $mensaje['tipo'] = 'danger';
            }
            break;

        case 'editar':
            $id = mysqli_real_escape_string($conn, $_POST['id']);
            if(is_numeric($id) && $id > 0 && $id == (int)$id) {
                $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
                $correo = mysqli_real_escape_string($conn, $_POST['correo']);
                // Si se ingresó una nueva contraseña, se hashea; si no, se conserva la anterior.
                if (!empty($_POST['contra'])) {
                    $contra = hash('sha256', $_POST['contra']);
                } else {
                    // Obtener la contraseña actual de la base de datos
                    $result = mysqli_query($conn, "SELECT contra FROM usuario WHERE id='$id' LIMIT 1");
                    $row = mysqli_fetch_assoc($result);
                    $contra = $row['contra'];
                }
                $cedula = mysqli_real_escape_string($conn, $_POST['cedula']);
                $sql = "UPDATE usuario SET nombre='$nombre', correo='$correo', contra='$contra', cedula='$cedula' WHERE id='$id'";
                if (mysqli_query($conn, $sql)) {
                    $mensaje['mensaje'] = "Usuario actualizado correctamente.";
                    $mensaje['tipo'] = 'success';
                } else {
                    $mensaje['mensaje'] = "Error al actualizar el usuario";
                    $mensaje['tipo'] = 'danger';
                }
                break;
            } else {
                $mensaje['mensaje'] = "ERROR.";
                $mensaje['tipo'] = 'danger';
            }

        case 'eliminar':
            $id = mysqli_real_escape_string($conn, $_POST['id']);
            if(is_numeric($id) && $id > 0 && $id == (int)$id) {
                $sql = "UPDATE usuario SET estado = 0 WHERE id='$id'";
                if (mysqli_query($conn, $sql)) {
                    $mensaje['mensaje'] = "Usuario eliminado correctamente.";
                    $mensaje['tipo'] = 'success';
                } else {
                    $mensaje['mensaje'] = "Error al eliminar el usuario";
                    $mensaje['tipo'] = 'danger';
                }
            } else {
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
  <table class="table table-striped table-sm">
 <thead>
    <tr>
      <th scope="col">Nombre</th>
      <th scope="col">Correo</th>
      <th scope="col">Cédula</th>
      <th scope="col">Fecha Creación</th>
      <th scope="col">Estado</th>
      <th scope="col">Acciones</th>
    </tr>
 </thead>
 <tbody>
    <?php
    $usuarios = mysqli_query($conn, "SELECT * FROM usuario WHERE estado != 0 ORDER BY nombre ASC");
    foreach ($usuarios as $usuario) {
      $estado_color = $usuario['estado'] == 1 ? 'success' : 'danger';
      $estado_nombre = $usuario['estado'] == 1 ? '<span data-feather="check-circle" class="align-text-bottom"></span>'
         : '<span data-feather="clock" class="align-text-bottom"></span>';
      echo "<tr id=\"usuario-{$usuario['id']}\">
                    <td>{$usuario['nombre']}</td>
                    <td>{$usuario['correo']}</td>
                    <td>{$usuario['cedula']}</td>
                    <td>{$usuario['fecha_creacion']}</td>
                    <td><span class=\"badge text-bg-$estado_color\">$estado_nombre</span></td>
                    <td>
                      <button onclick='editarUsuario({$usuario['id']})' class='btn btn-primary'><span data-feather=\"edit\" class=\"align-text-bottom\"></span></button>
                      <button onclick='eliminarUsuario({$usuario['id']})' class='btn btn-danger'><span data-feather=\"trash\" class=\"align-text-bottom\"></span></button>
                    </td>
                 </tr>";
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
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Nombre del usuario">
                      </div>
                      <div class="mb-3">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correo" name="correo" required placeholder="Correo del usuario">
                      </div>
                      <div class="mb-3">
                            <label for="contra" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="contra" name="contra" required placeholder="Contraseña">
                      </div>
                      <div class="mb-3">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" required placeholder="Cédula del usuario">
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
                 <input type="hidden" name="id" id="id_usuario">
                 <div class="modal-body">
                      <div class="mb-3">
                            <label for="nombreEdit" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombreEdit" name="nombre" required placeholder="Nombre del usuario">
                      </div>
                      <div class="mb-3">
                            <label for="correoEdit" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correoEdit" name="correo" required placeholder="Correo del usuario">
                      </div>
                      <div class="mb-3">
                            <label for="contraEdit" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="contraEdit" name="contra" required placeholder="Contraseña">
                      </div>
                      <div class="mb-3">
                            <label for="cedulaEdit" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="cedulaEdit" name="cedula" required placeholder="Cédula del usuario">
                      </div>
                      <div class="text-center">
                            <button type="submit" name="accion" value="editar" class="btn btn-success">Guardar</button>
                      </div>
                 </div>
            </form>
      </div>
 </div>
</div>

<script>
  function editarUsuario(id_usuario) {
 var editModal = new bootstrap.Modal(document.getElementById('editModal'));
 editModal.show();
 document.getElementById('id_usuario').value = id_usuario;
 document.getElementById('spanNumUsuario').innerText = id_usuario;
 const fila = document.getElementById('usuario-' + id_usuario);
 const nombre = fila.children[0].innerText;
 const correo = fila.children[1].innerText;
 const cedula = fila.children[2].innerText;
 document.getElementById('nombreEdit').value = nombre;
 document.getElementById('correoEdit').value = correo;
 document.getElementById('cedulaEdit').value = cedula;
 // Contraseña no se puede mostrar por seguridad, se deja vacía
 document.getElementById('contraEdit').value = '';
  }

  function eliminarUsuario(id_usuario) {
 if (confirm("¿Estás seguro de eliminar el usuario #" + id_usuario + "?")) {
    const form = document.createElement('form');
    form.method = 'post';
    form.action = '';
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'id';
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
</script>


<?php
require '../layout/footer.php';