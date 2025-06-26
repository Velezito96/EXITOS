<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || $_SESSION['idCargo'] != 3) {
    header("Location: login.php");
    exit();
}

$idCliente = $_SESSION['idCliente'];
$idEncuesta = $_POST['idEncuesta'] ?? null;
$idCurso = $_POST['idCurso'] ?? null;
$opcionesSeleccionadas = $_POST['opciones'] ?? [];

// Validación de entrada
if (!$idEncuesta || empty($opcionesSeleccionadas)) {
    $_SESSION['error'] = "Debes seleccionar al menos una opción.";
    header("Location: encuestas.php?idCurso=$idCurso");
    exit();
}

// Validar que la encuesta está activa
$stmt = $pdo->prepare("SELECT * FROM encuestas WHERE idEncuesta = ? AND (fechaCierre IS NULL OR fechaCierre > NOW())");
$stmt->execute([$idEncuesta]);
$encuesta = $stmt->fetch();

if (!$encuesta) {
    $_SESSION['error'] = "Encuesta no válida o ya cerrada.";
    header("Location: encuestas.php?idCurso=$idCurso");
    exit();
}

// Validar que no ha respondido antes
$stmt = $pdo->prepare("SELECT COUNT(*) FROM encuesta_respuestas WHERE idEncuesta = ? AND idCliente = ?");
$stmt->execute([$idEncuesta, $idCliente]);
if ($stmt->fetchColumn() > 0) {
    $_SESSION['error'] = "Ya respondiste esta encuesta.";
    header("Location: encuestas.php?idCurso=$idCurso");
    exit();
}

// Validar si solo se permite una opción
if (!$encuesta['multiple'] && count($opcionesSeleccionadas) > 1) {
    $_SESSION['error'] = "Solo puedes seleccionar una opción en esta encuesta.";
    header("Location: encuestas.php?idCurso=$idCurso");
    exit();
}

// Insertar respuestas
$stmtInsert = $pdo->prepare("INSERT INTO encuesta_respuestas (idEncuesta, idCliente, idOpcion) VALUES (?, ?, ?)");
foreach ($opcionesSeleccionadas as $idOpcion) {
    $stmtInsert->execute([$idEncuesta, $idCliente, $idOpcion]);
}

// Redirigir de vuelta
$_SESSION['success'] = "Gracias por responder.";
header("Location: encuestas.php?idCurso=$idCurso");
exit();
