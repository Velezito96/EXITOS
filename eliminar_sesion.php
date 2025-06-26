<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || !in_array($_SESSION['idCargo'], [1, 2])) {
    header("Location: login.php");
    exit();
}

$idSesion = $_GET['idSesion'] ?? null;
$idCurso = $_GET['idCurso'] ?? null;
$idProfesor = $_SESSION['idCliente'];

if (!$idSesion || !$idCurso) {
    $_SESSION['error'] = "Parámetros faltantes.";
    header("Location: gestionar_cursos.php");
    exit();
}

// Verificar que el curso pertenezca al profesor
$stmt = $pdo->prepare("SELECT COUNT(*) FROM grupos WHERE idCurso = ? AND idProfesor = ?");
$stmt->execute([$idCurso, $idProfesor]);
if ($stmt->fetchColumn() == 0) {
    $_SESSION['error'] = "No tienes permiso para eliminar esta sesión.";
    header("Location: sesiones.php?idCurso=$idCurso");
    exit();
}

// Eliminar archivo si existe
$stmt = $pdo->prepare("SELECT archivo FROM sesiones WHERE idSesion = ?");
$stmt->execute([$idSesion]);
$archivo = $stmt->fetchColumn();
if ($archivo && file_exists("archivos/$archivo")) {
    unlink("archivos/$archivo");
}

// Eliminar la sesión
$stmt = $pdo->prepare("DELETE FROM sesiones WHERE idSesion = ?");
$stmt->execute([$idSesion]);

$_SESSION['success'] = "Sesión eliminada correctamente.";
header("Location: sesiones.php?idCurso=$idCurso");
exit();
