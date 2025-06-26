<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

// Verificar si el usuario ha iniciado sesión y es administrador
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario'];

// Verificar el rol de usuario (1: Administrador)
$sqlUsuario = "SELECT idCargo FROM cliente WHERE usuario = :usuario LIMIT 1";
$stmtUsuario = $pdo->prepare($sqlUsuario);
$stmtUsuario->execute(['usuario' => $usuario]);
$user = $stmtUsuario->fetch();

if (!$user || $user['idCargo'] != 1) {
    echo "No tienes permisos para agregar horarios.";
    exit();
}

// Obtener los datos del formulario
$idCurso = $_POST['idCurso'];
$diaSemana = $_POST['diaSemana'];
$horaInicio = $_POST['horaInicio'];
$horaFin = $_POST['horaFin'];

// Insertar los horarios en la base de datos
$sql = "
    INSERT INTO horarios (idCurso, diaSemana, horaInicio, horaFin)
    VALUES (:idCurso, :diaSemana, :horaInicio, :horaFin)
";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'idCurso' => $idCurso,
    'diaSemana' => $diaSemana,
    'horaInicio' => $horaInicio,
    'horaFin' => $horaFin
]);

// Redirigir al usuario con un mensaje de éxito
$_SESSION['success'] = "Horario agregado exitosamente.";
header("Location: gestionar_horarios.php");
exit();
?>
