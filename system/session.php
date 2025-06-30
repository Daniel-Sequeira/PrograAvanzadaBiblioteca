<?php
session_start();

if(!isset($_SESSION['usuario_id'])) {
    header("Location: logout.php");
    exit();
}
require 'config.php';