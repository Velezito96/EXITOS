<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || !in_array($_SESSION['idCargo'], [1, 2])) {
    header("Location: login.php");
    exit();
}

$idEvaluacion = $_GET['idEvaluacion'] ?? null;
$idProfesor = $_SESSION['idCliente'];

if (!$idEvaluacion) {
    $_SESSION['error'] = "Evaluación no especificada.";
    header("Location: gestionar_cursos.php");
    exit();
}

// Validar que el profesor tenga acceso a la evaluación
$stmt = $pdo->prepare("SELECT e.titulo, e.idCurso FROM evaluaciones e JOIN grupos g ON g.idCurso = e.idCurso WHERE e.id = ? AND g.idProfesor = ?");
$stmt->execute([$idEvaluacion, $idProfesor]);
$evaluacion = $stmt->fetch();

if (!$evaluacion) {
    $_SESSION['error'] = "Acceso denegado a esta evaluación.";
    header("Location: gestionar_cursos.php");
    exit();
}

// Obtener preguntas
$stmt = $pdo->prepare("SELECT * FROM preguntas WHERE idEvaluacion = ? ORDER BY idPregunta ASC");
$stmt->execute([$idEvaluacion]);
$preguntas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Banco de Preguntas - <?php echo htmlspecialchars($evaluacion['titulo']); ?></title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Banco de Preguntas: <?php echo htmlspecialchars($evaluacion['titulo']); ?></h2>

    <a href="agregar_pregunta.php?idEvaluacion=<?php echo $idEvaluacion; ?>" class="btn btn-success mb-3">+ Agregar Nueva Pregunta</a>

    <?php if (count($preguntas) > 0): ?>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Pregunta</th>
                    <th>Alternativas</th>
                    <th>Respuesta Correcta</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($preguntas as $p): ?>
                    <tr>
                        <td><?php echo nl2br(htmlspecialchars($p['texto'])); ?></td>
                        <td>
                            A) <?php echo htmlspecialchars($p['opcionA']); ?><br>
                            B) <?php echo htmlspecialchars($p['opcionB']); ?><br>
                            C) <?php echo htmlspecialchars($p['opcionC']); ?><br>
                            D) <?php echo htmlspecialchars($p['opcionD']); ?>
                        </td>
                        <td><?php echo strtoupper($p['respuesta']); ?></td>
                        <td>
                            <a href="editar_pregunta.php?idPregunta=<?php echo $p['idPregunta']; ?>&idEvaluacion=<?php echo $idEvaluacion; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="eliminar_pregunta.php?idPregunta=<?php echo $p['idPregunta']; ?>&idEvaluacion=<?php echo $idEvaluacion; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Deseas eliminar esta pregunta?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No hay preguntas registradas para esta evaluación.</div>
    <?php endif; ?>

    <a href="evaluaciones.php?idCurso=<?php echo $evaluacion['idCurso']; ?>" class="btn btn-secondary mt-4">Volver a Evaluaciones</a>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>
