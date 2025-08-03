<?php
session_start();

if(!isset($_SESSION['usuario_id'])) {
    header("Location: ../logout.php");
    exit();
}


require 'config.php';

$usario = mysqli_query($conn, "SELECT * FROM usuario WHERE id = '{$_SESSION['usuario_id']}' LIMIT 1");
if(mysqli_num_rows($usario) == 0) {
    header("Location: ../logout.php");
    exit();
}
$usuario = mysqli_fetch_assoc($usario);
if($usuario['estado'] != 1) {
    header("Location: ../logout.php");
    exit();
}