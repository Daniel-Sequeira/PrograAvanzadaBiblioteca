<?php
require '../system/session.php';
require '../layout/header.php';
$mensaje = [];
if (isset($_POST['accion'])) {
  switch ($_POST['accion']) {
    case 'agregar':
      $codigo = mysqli_real_escape_string($conn, $_POST['codigo']);
      $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
      $autor = mysqli_real_escape_string($conn, $_POST['autor']);
      $genero = mysqli_real_escape_string($conn, $_POST['genero']);
      $anno = mysqli_real_escape_string($conn, $_POST['anno']);

      $sql = "INSERT INTO libro (codigo, nombre, autor, genero, anno) VALUES ('$codigo', '$nombre', '$autor', '$genero', '$anno')";
      if (mysqli_query($conn, $sql)) {
        $mensaje['mensaje'] = "Libro agregado correctamente.";
        $mensaje['tipo'] = 'success';
      } else {
        $mensaje['mensaje'] = "Error al agregar el libro";
        $mensaje['tipo'] = 'danger';
      }
      break;

    case 'editar':
      $id_libro = mysqli_real_escape_string($conn, $_POST['id_libro']);
      if(is_numeric($id_libro) && $id_libro > 0 && $id_libro == (int)$id_libro) {
        $codigo = mysqli_real_escape_string($conn, $_POST['codigo']);
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $autor = mysqli_real_escape_string($conn, $_POST['autor']);
        $genero = mysqli_real_escape_string($conn, $_POST['genero']);
        $anno = mysqli_real_escape_string($conn, $_POST['anno']);
        $sql = "UPDATE libro SET codigo='$codigo', nombre='$nombre', autor='$autor', genero='$genero', anno='$anno' WHERE id='$id_libro'";
        if (mysqli_query($conn, $sql)) {
          $mensaje['mensaje'] = "Libro actualizado correctamente.";
          $mensaje['tipo'] = 'success';
        } else {
          $mensaje['mensaje'] = "Error al actualizar el libro";
          $mensaje['tipo'] = 'danger';
        }
        break;
      }else{
        $mensaje['mensaje'] = "ERROR.";
        $mensaje['tipo'] = 'danger';
      }

    case 'eliminar':
      $id_libro = mysqli_real_escape_string($conn, $_POST['id_libro']);
      if(is_numeric($id_libro) && $id_libro > 0 && $id_libro == (int)$id_libro) {
        $sql = "UPDATE libro SET estado = 0 WHERE id='$id_libro'";
        if (mysqli_query($conn, $sql)) {
          $mensaje['mensaje'] = "Libro eliminado correctamente.";
          $mensaje['tipo'] = 'success';
        } else {
          $mensaje['mensaje'] = "Error al eliminar el libro";
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
    <h1 class="h2">Mantenimientos de Libros</h1>
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
    <table id="tablaLibros" class="table table-striped table-sm w-100">
        <thead>
            <tr>
                <th scope="col">Codigo</th>
                <th scope="col">Nombre</th>
                <th scope="col">Autor</th>
                <th scope="col">Género</th>
                <th scope="col">Año de Publicación</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
      $libros = mysqli_query($conn, "SELECT * FROM libro WHERE estado != 0 ORDER BY nombre ASC");
      foreach ($libros as $libro) {
        $estado_color = $libro['estado'] == 1 ? 'success' : 'danger';
        $estado_nombre = $libro['estado'] == 1 ? '<span data-feather="check-circle" class="align-text-bottom"></span>'
          : '<span data-feather="clock" class="align-text-bottom"></span>';
        echo "<tr id=\"libro-{$libro['id']}\">
                  <td>{$libro['codigo']}</td>
                  <td>{$libro['nombre']}</td>
                  <td>{$libro['autor']}</td>
                  <td>{$libro['genero']}</td>
                  <td>{$libro['anno']}</td>
                  <td><span class=\"badge text-bg-$estado_color\">$estado_nombre</span></td>
                  <td>
                    <button onclick='editarLibro({$libro['id']})' class='btn btn-primary'><span data-feather=\"edit\" class=\"align-text-bottom\"></span></button>
                    <button onclick='eliminarLibro({$libro['id']})' class='btn btn-danger'><span data-feather=\"trash\" class=\"align-text-bottom\"></span></button>
                  </td>
                </tr>";
      }
      ?>
        </tbody>
    </table>
  </div>

    <?php
  $year_actual = date('Y');
  $select_years = "";
  for ($year = $year_actual; $year >= 1900; $year--) {
    $select_years .= "<option value=\"$year\">$year</option>";
  }
  ?>
    <!-- Modal de agregar libro -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addModalLabel">Nuevo Libro</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="codigo" class="form-label">Codigo</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" required
                                placeholder="LB05-1540">
                        </div>
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required
                                placeholder="Las aventuras de Sherlock Holmes">
                        </div>
                        <div class="mb-3">
                            <label for="autor" class="form-label">Autor</label>
                            <input type="text" class="form-control" id="autor" name="autor" required
                                placeholder="Arthur Conan Doyle">
                        </div>
                        <div class="mb-3">
                            <label for="genero" class="form-label">Género</label>
                            <input type="text" class="form-control" id="genero" name="genero" required
                                placeholder="Detective, Misterio">
                        </div>
                        <div class="mb-3">
                            <label for="anno" class="form-label">Año de Publicación</label>
                            <select class="form-select" id="anno" name="anno" required>
                                <?= $select_years ?>
                            </select>
                        </div>
                        <div class="text-center">
                            <button type="submit" name="accion" value="agregar" class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal de editar libro -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Editar Libro #<span id="spanNumLibro"></span></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="">
                    <input type="hidden" name="id_libro" id="id_libro">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="codigoEdit" class="form-label">Codigo</label>
                            <input type="text" class="form-control" id="codigoEdit" name="codigo" required
                                placeholder="LB05-1540">
                        </div>
                        <div class="mb-3">
                            <label for="nombreEdit" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombreEdit" name="nombre" required
                                placeholder="Las aventuras de Sherlock Holmes">
                        </div>
                        <div class="mb-3">
                            <label for="autorEdit" class="form-label">Autor</label>
                            <input type="text" class="form-control" id="autorEdit" name="autor" required
                                placeholder="Arthur Conan Doyle">
                        </div>
                        <div class="mb-3">
                            <label for="generoEdit" class="form-label">Género</label>
                            <input type="text" class="form-control" id="generoEdit" name="genero" required
                                placeholder="Detective, Misterio">
                        </div>
                        <div class="mb-3">
                            <label for="annoEdit" class="form-label">Año de Publicación</label>
                            <select class="form-select" id="annoEdit" name="anno" required>
                                <?= $select_years ?>
                            </select>
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
        new DataTable('#tablaLibros', {
            responsive: true,
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.0.7/i18n/es-ES.json'
            }
        });
    });


    function editarLibro(id_libro) {
        var editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
        document.getElementById('id_libro').value = id_libro;
        document.getElementById('spanNumLibro').innerText = id_libro;
        const fila = document.getElementById('libro-' + id_libro);
        const codigo = fila.children[0].innerText;
        const nombre = fila.children[1].innerText;
        const autor = fila.children[2].innerText;
        const genero = fila.children[3].innerText;
        const anno = fila.children[4].innerText;
        document.getElementById('codigoEdit').value = codigo;
        document.getElementById('nombreEdit').value = nombre;
        document.getElementById('autorEdit').value = autor;
        document.getElementById('generoEdit').value = genero;
        document.getElementById('annoEdit').value = anno;
    }

    function eliminarLibro(id_libro) {
        if (confirm("¿Estás seguro de eliminar el libro #" + id_libro + "?")) {
            const form = document.createElement('form');
            form.method = 'post';
            form.action = '';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id_libro';
            input.value = id_libro;
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