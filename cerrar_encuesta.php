<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

// Validación de sesión
if (!isset($_SESSION['idCliente']) || !in_array($_SESSION['idCargo'], [1, 2])) {
    header("Location: login.php");
    exit();
}

$idEncuesta = $_POST['idEncuesta'] ?? null;
$idUsuario = $_SESSION['idCliente'];

if (!$idEncuesta) {
    echo "Encuesta no especificada.";
    exit();
}

// Verificar que la encuesta existe y que el usuario tiene permiso
$stmt = $pdo->prepare("SELECT idProfesor, idCurso FROM encuestas WHERE idEncuesta = ?");
$stmt->execute([$idEncuesta]);
$encuesta = $stmt->fetch();

if (!$encuesta) {
    echo "Encuesta no encontrada.";
    exit();
}

$esPropietario = $encuesta['idProfesor'] == $idUsuario;
$esAdmin = $_SESSION['idCargo'] == 1;

if (!$esPropietario && !$esAdmin) {
    echo "No tienes permiso para cerrar esta encuesta.";
    exit();
}

// Cerrar la encuesta (fecha actual)
$stmt = $pdo->prepare("UPDATE encuestas SET fechaCierre = NOW() WHERE idEncuesta = ?");
$stmt->execute([$idEncuesta]);

// Redirigir de vuelta a la gestión de encuestas
header("Location: encuestas_profesor.php?idCurso=" . $encuesta['idCurso']);
exit();
?>
