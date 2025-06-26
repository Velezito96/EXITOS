<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || !in_array($_SESSION['idCargo'], [1, 2])) {
    header("Location: login.php");
    exit();
}

$idProfesor = $_SESSION['idCliente'];
$idCurso = $_GET['idCurso'] ?? null;

if (!$idCurso) {
    echo "Curso no especificado.";
    exit();
}

// Validar acceso como profesor del curso
$stmt = $pdo->prepare("SELECT COUNT(*) FROM grupos WHERE idCurso = ? AND idProfesor = ?");
$stmt->execute([$idCurso, $idProfesor]);
if ($stmt->fetchColumn() == 0 && $_SESSION['idCargo'] != 1) {
    echo "No tienes acceso a este curso.";
    exit();
}

// Obtener tareas del curso
$stmt = $pdo->prepare("SELECT * FROM tareas WHERE idCurso = ? ORDER BY fechaLimite DESC");
$stmt->execute([$idCurso]);
$tareas = $stmt->fetchAll();

function estadoTarea($limite) {
    return (strtotime($limite) > time()) ? 'Abierta' : 'Cerrada';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Tareas</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2 class="mb-4">Tareas del Curso</h2>
    <a href="crear_tarea.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-success mb-3">+ Crear Nueva Tarea</a>

    <?php if (count($tareas) > 0): ?>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Título</th>
                    <th>Fecha Límite</th>
                    <th>Intentos</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tareas as $t): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($t['titulo']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($t['fechaLimite'])); ?></td>
                        <td><?php echo $t['maxIntentos']; ?></td>
                        <td><?php echo estadoTarea($t['fechaLimite']); ?></td>
                        <td>
                            <a href="ver_entregas_tarea.php?idTarea=<?php echo $t['idTarea']; ?>" class="btn btn-sm btn-outline-primary">Ver Entregas</a>
<a href="calificar_tarea.php?idTarea=<?php echo $t['idTarea']; ?>" class="btn btn-sm btn-outline-success">Calificar</a>
                            <a href="editar_tarea.php?idTarea=<?php echo $t['idTarea']; ?>" class="btn btn-sm btn-outline-warning">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No hay tareas registradas para este curso.</div>
    <?php endif; ?>

    <a href="gestionar_cursos.php" class="btn btn-secondary mt-4">Volver a Cursos</a>
</body>
</html>
