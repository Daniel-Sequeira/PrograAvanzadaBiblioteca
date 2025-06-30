<?php
session_start();
if(isset($_SESSION['usuario_id'])) {
    header("Location: pages/index.php");
    exit();
}
require 'system/config.php';
$mensaje_error = '';
//login
if (isset($_POST['usuario']) && isset($_POST['contra']) && !empty($_POST['usuario']) && !empty($_POST['contra'])) {
  $usuario = mysqli_real_escape_string($conn, $_POST['usuario']);
  $contra = $_POST['contra'];
  $contra = hash('sha256', $contra); // -> 6dcd4ce23d88e2ee9568ba546c007c63a0b3f1f5b7f8e9b1c2f3a4e5b6c7d8e9

  // Verificar si el usuario existe
  $result = mysqli_query($conn, "SELECT id, contra FROM usuario WHERE (cedula = '$usuario' OR correo = '$usuario') AND estado=1 LIMIT 1");

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    if ($row['contra'] == $contra) {
      $_SESSION['usuario_id'] = $row['id'];
      header("Location: pages/index.php");
      exit();
    } else {
      $mensaje_error = 'Contraseña incorrecta';
    }
  } else {
    $mensaje_error = 'Cedula o Correo Electronico incorrecto';
  }
}
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.104.2">
  <title>Login - Biblioteca</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/login.css" rel="stylesheet">
</head>

<body class="text-center">

  <main class="form-signin w-100 m-auto">
    <form action="" method="post">
      <img class="mb-4" src="img/bootstrap-logo.svg" alt="" width="72" height="57">
      <h1 class="h3 mb-3 fw-normal">Inicio de Sesion</h1>

      <div class="form-floating">
        <input name="usuario" type="text" class="form-control" id="floatingInput" placeholder="1-1234-5678 | name@example.com">
        <label for="floatingInput">Cedula o Correo Electronico</label>
      </div>
      <div class="form-floating">
        <input name="contra" type="password" class="form-control" id="floatingPassword" placeholder="Password">
        <label for="floatingPassword">Contraseña</label>
      </div>

      <?php
      if (!empty($mensaje_error)) {
      ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="mensaje_error">
          <strong>Ups!</strong> <?= $mensaje_error ?>
          <button type="button" class="btn-close" onclick="mensaje_error.style.display='none';"></button>
        </div>
      <?php
      }
      ?>
      <button class="w-100 btn btn-lg btn-primary" type="submit">Ingresar</button>
      <p class="mt-5 mb-3 text-muted">Biblioteca UISIL &copy; <?= date('Y') ?></p>
    </form>
  </main>



</body>

</html>