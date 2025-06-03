<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente'])) {
    header("Location: login.php");
    exit();
}

$idCliente = $_SESSION['idCliente'];

$sql = "SELECT c.*, m.estado, m.fechaMatricula 
        FROM cursos c 
        JOIN matricula m ON c.idCurso = m.idCurso
        WHERE m.idCliente = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$idCliente]);
$cursosMatriculados = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Mis Cursos - Aula Virtual</title>
    <link href="BOOTSTRAP/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h1>Mis Cursos Matriculados</h1>

    <?php if (count($cursosMatriculados) > 0): ?>
        <?php foreach ($cursosMatriculados as $curso): ?>
            <div class="card mb-3 p-3">
                <h3><?php echo htmlspecialchars($curso['nombreCurso']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($curso['descripcion'])); ?></p>
                <p><strong>Estado:</strong> <?php echo htmlspecialchars($curso['estado']); ?></p>
                <p><strong>Fecha de matrícula:</strong> <?php echo htmlspecialchars($curso['fechaMatricula']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info text-center mt-4">
            <p>Aún no te has matriculado en ningún curso.</p>
            <a href="dashboard.php" class="btn btn-success">Volver al Dashboard</a>
        </div>
    <?php endif; ?>
</div>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>
