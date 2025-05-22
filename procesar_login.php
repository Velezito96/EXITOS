<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

$usuario = $_POST['usuario'];
$clave = $_POST['clave'];

$sql = "SELECT * FROM cliente WHERE usuario = :usuario LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['usuario' => $usuario]);

$cliente = $stmt->fetch();

if ($cliente && password_verify($clave, $cliente['clave'])) {
    $_SESSION['usuario'] = $cliente['usuario'];
    $_SESSION['rol'] = $cliente['idCargo'];
    header("Location: index.php");
    exit();
} else {
    header("Location: login.php?error=Credenciales incorrectas");
    exit();
}
