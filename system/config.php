<?php
//conexion a la base de datos mysql con mysqli
$host = 'localhost';
$usuario = 'root';
$clave = '';
$base_datos = 'biblioteca_bd';

$conn = mysqli_connect($host, $usuario, $clave, $base_datos);
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}