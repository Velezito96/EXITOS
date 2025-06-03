<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
$pdo = conexionBD();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idCliente = $_POST['idCliente'] ?? null;
    $idCurso = $_POST['idCurso'] ?? null;
    $idNivel = $_POST['idNivel'] ?? null;

    if (!$idCliente || !$idCurso || !$idNivel) {
        die("Datos incompletos para matricular.");
    }

    // Validar vacantes (max 30 por curso + nivel)
    $sqlVacantes = "SELECT COUNT(*) as total FROM solicitud WHERE idCurso = :idCurso AND idNivel = :idNivel AND estado = 1";
    $stmtVacantes = $pdo->prepare($sqlVacantes);
    $stmtVacantes->execute(['idCurso' => $idCurso, 'idNivel' => $idNivel]);
    $count = $stmtVacantes->fetch()['total'];

    if ($count >= 30) {
        header("Location: cursos_disponibles.php?mensaje=No hay vacantes disponibles para este curso y nivel");
        exit();
    }

    // Verificar si ya est mmatriculado en ese curso y nivel
    $sqlCheck = "SELECT * FROM solicitud WHERE idCliente = :idCliente AND idCurso = :idCurso AND idNivel = :idNivel LIMIT 1";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute(['idCliente' => $idCliente, 'idCurso' => $idCurso, 'idNivel' => $idNivel]);
    $existe = $stmtCheck->fetch();

    if ($existe) {
        header("Location: cursos_disponibles.php?mensaje=Ya estás matriculado en este curso y nivel");
        exit();
    }

    // Insertar matrícula  si el estado esta en 1 esta activo (no confundir xd)
    $sqlInsert = "INSERT INTO solicitud (idCliente, idCurso, idNivel, fecha, estado) VALUES (:idCliente, :idCurso, :idNivel, :fecha, 1)";
    $stmtInsert = $pdo->prepare($sqlInsert);
    $fecha = date('Ymd');
    $stmtInsert->execute([
        'idCliente' => $idCliente,
        'idCurso' => $idCurso,
        'idNivel' => $idNivel,
        'fecha' => $fecha
    ]);

    header("Location: mis_cursos.php?mensaje=Matriculación exitosa");
    exit();
} else {
    header("Location: cursos_disponibles.php");
    exit();
}
