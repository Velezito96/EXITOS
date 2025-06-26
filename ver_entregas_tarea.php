<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || !in_array($_SESSION['idCargo'], [1, 2])) {
    header("Location: login.php");
    exit();
}

$idTarea = $_GET['idTarea'] ?? null;
if (!$idTarea) {
    echo "Tarea no especificada.";
    exit();
}

// Obtener detalles de la tarea
$stmt = $pdo->prepare("SELECT t.*, c.nombreCurso FROM tareas t JOIN cursos c ON t.idCurso = c.idCurso WHERE t.idTarea = ?");
$stmt->execute([$idTarea]);
$tarea = $stmt->fetch();
if (!$tarea) {
    echo "Tarea no encontrada.";
    exit();
}

// Obtener entregas de estudiantes
$stmt = $pdo->prepare("SELECT e.*, cl.apellidosNombres FROM entregas_tarea e JOIN cliente cl ON e.idCliente = cl.idCliente WHERE e.idTarea = ? ORDER BY e.fechaEnvio ASC");
$stmt->execute([$idTarea]);
$entregas = $stmt->fetchAll();

function estaRetrasado($fechaEnvio, $fechaLimite) {
    return strtotime($fechaEnvio) > strtotime($fechaLimite);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Entregas de Tarea</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
    <style>
        .retrasado {
            background-color: #f8d7da !important;
        }
    </style>
</head>
<body class="container py-4">
    <h2>Entregas de: <?php echo htmlspecialchars($tarea['titulo']); ?></h2>
    <p><strong>Curso:</strong> <?php echo htmlspecialchars($tarea['nombreCurso']); ?></p>
    <p><strong>Fecha límite:</strong> <?php echo date('d/m/Y H:i', strtotime($tarea['fechaLimite'])); ?></p>

    <?php if (count($entregas) > 0): ?>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Estudiante</th>
                    <th>Archivo</th>
                    <th>Fecha de Envío</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entregas as $e): ?>
                    <tr class="<?php echo estaRetrasado($e['fechaEnvio'], $tarea['fechaLimite']) ? 'retrasado' : ''; ?>">
                        <td><?php echo htmlspecialchars($e['apellidosNombres']); ?></td>
                        <td><a href="uploads/tareas/<?php echo htmlspecialchars($e['archivo']); ?>" target="_blank">Ver Archivo</a></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($e['fechaEnvio'])); ?></td>
                        <td><?php echo estaRetrasado($e['fechaEnvio'], $tarea['fechaLimite']) ? 'Fuera de plazo' : 'A tiempo'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Aún no hay entregas registradas para esta tarea.</div>
    <?php endif; ?>

    <a href="tareas_profesor.php?idCurso=<?php echo $tarea['idCurso']; ?>" class="btn btn-secondary mt-4">Volver a Tareas</a>
</body>
</html>
