<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || !in_array($_SESSION['idCargo'], [1, 2, 3])) {
    header("Location: login.php");
    exit();
}

$idUsuario = $_SESSION['idCliente'];
$idCargo = $_SESSION['idCargo']; // 1 = admin, 2 = profesor, 3 = estudiante
$idCurso = $_GET['idCurso'] ?? null;

if (!$idCurso) {
    echo "Curso no especificado.";
    exit();
}

// Validar si el usuario tiene acceso al curso
if ($idCargo == 2) { // Profesor
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM grupos WHERE idCurso = ? AND idProfesor = ?");
    $stmt->execute([$idCurso, $idUsuario]);
    if ($stmt->fetchColumn() == 0) {
        echo "Acceso denegado al curso.";
        exit();
    }
} elseif ($idCargo == 3) { // Estudiante
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM matricula WHERE idCurso = ? AND idCliente = ? AND estado = 'Activo'");
    $stmt->execute([$idCurso, $idUsuario]);
    if ($stmt->fetchColumn() == 0) {
        echo "No estás matriculado en este curso.";
        exit();
    }
}

// Obtener evaluaciones del curso
$stmt = $pdo->prepare("SELECT * FROM evaluaciones WHERE idCurso = ? ORDER BY fecha_inicio DESC");
$stmt->execute([$idCurso]);
$evaluaciones = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Evaluaciones del Curso</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3 class="mb-4">Evaluaciones del Curso</h3>

    <?php if ($idCargo == 2 || $idCargo == 1): ?>
        <a href="crear_evaluacion.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-success mb-3">
            Crear nueva evaluación
        </a>
    <?php endif; ?>

    <?php if (count($evaluaciones) > 0): ?>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Título</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Duración</th>
                    <th>Intentos</th>
                    <?php if ($idCargo != 3): ?><th>Acciones</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($evaluaciones as $e): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($e['titulo']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($e['fecha_inicio'])); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($e['fecha_fin'])); ?></td>
                        <td><?php echo $e['duracion']; ?> min</td>
                        <td><?php echo $e['intentos_max']; ?></td>

                        <?php if ($idCargo == 2 || $idCargo == 1): ?>
                        <td>
                            <a href="banco_preguntas.php?idEvaluacion=<?php echo $e['id']; ?>" class="btn btn-sm btn-outline-primary">Preguntas</a>
                            <a href="editar_evaluacion.php?id=<?php echo $e['id']; ?>" class="btn btn-sm btn-outline-warning">Editar</a>
                            <a href="ver_resultados.php?idEvaluacion=<?php echo $e['id']; ?>" class="btn btn-sm btn-outline-success">Resultados</a>
                        </td>
                        <?php endif; ?>

                        <?php if ($idCargo == 3): ?>
                        <td>
                            <a href="iniciar_examen.php?idEvaluacion=<?php echo $e['id']; ?>" class="btn btn-sm btn-outline-primary">Rendir</a>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No hay evaluaciones registradas aún.</div>
    <?php endif; ?>

    <a href="panel_curso.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-secondary mt-3">Volver al Curso</a>
</div>
<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>
