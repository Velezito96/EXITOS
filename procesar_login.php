<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

$usuario = $_POST['usuario'] ?? '';
$clave = $_POST['clave'] ?? '';

if (empty($usuario) || empty($clave)) {
    header("Location: login.php?error=Debe ingresar usuario y clave");
    exit();
}

$sql = "SELECT * FROM cliente WHERE usuario = :usuario LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['usuario' => $usuario]);

$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cliente && password_verify($clave, $cliente['clave'])) {
    // Guardamos usuario e idCliente en sesión
    $_SESSION['usuario'] = $cliente['usuario'];
    $_SESSION['idCliente'] = $cliente['idCliente'];
    // Opcional: guardar rol o idCargo si lo necesitas
    $_SESSION['rol'] = $cliente['idCargo'] ?? null;

    // Redirigir al dashboard en lugar del index
    header("Location: dashboard.php");
    exit();
} else {
    header("Location: login.php?error=Credenciales incorrectas");
    exit();
}
