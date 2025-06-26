<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

$usuario = $_POST['usuario'];
$clave = $_POST['clave'];

// Verificar si el usuario existe
$sql = "SELECT idCliente, usuario, clave FROM cliente WHERE usuario = :usuario LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['usuario' => $usuario]);

$cliente = $stmt->fetch();

if ($cliente && password_verify($clave, $cliente['clave'])) {
    // Almacenar el ID del cliente en la sesión
    $_SESSION['idCliente'] = $cliente['idCliente'];  // Guardamos el idCliente en la sesión
    $_SESSION['usuario'] = $cliente['usuario'];      // Guardamos el nombre de usuario en la sesión
    header("Location: dashboard.php");  // Redirigimos al dashboard después del login exitoso
    exit();
} else {
    header("Location: login.php?error=Credenciales incorrectas");  // Si las credenciales son incorrectas
    exit();
}
?>
