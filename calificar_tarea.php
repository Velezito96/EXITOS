<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || $_SESSION['idCargo'] == 3) {
    header("Location: login.php");
    exit();
}

$idTarea = $_GET['idTarea'] ?? null;
if (!$idTarea) {
    echo "Tarea no especificada.";
    exit();
}

// Obtener tarea
$stmt = $pdo->prepare("SELECT t.*, c.nombreCurso FROM tareas t JOIN cursos c ON t.idCurso = c.idCurso WHERE t.idTarea = ?");
$stmt->execute([$idTarea]);
$tarea = $stmt->fetch();
if (!$tarea) {
    echo "Tarea no encontrada.";
    exit();
}

// Obtener entregas con nota y observaciones
$stmt = $pdo->prepare("SELECT e.*, cl.apellidosNombres FROM entregas_tarea e JOIN cliente cl ON e.idCliente = cl.idCliente WHERE e.idTarea = ?");
$stmt->execute([$idTarea]);
$entregas = $stmt->fetchAll();

// Guardar calificaciones
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['notas'] as $idEntrega => $nota) {
        $observacion = $_POST['observaciones'][$idEntrega] ?? '';
        $stmt = $pdo->prepare("UPDATE entregas_tarea SET nota = ?, observacion = ? WHERE idEntrega = ?");
        $stmt->execute([$nota, $observacion, $idEntrega]);
    }
    $_SESSION['success'] = "Calificaciones guardadas.";
    header("Location: calificar_tarea.php?idTarea=$idTarea");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calificar Entregas</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Calificar: <?php echo htmlspecialchars($tarea['titulo']); ?></h2>
    <p><strong>Curso:</strong> <?php echo htmlspecialchars($tarea['nombreCurso']); ?></p>

    <?php if (count($entregas) > 0): ?>
        <form method="post">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Estudiante</th>
                        <th>Archivo</th>
                        <th>Nota</th>
                        <th>Observación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entregas as $e): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($e['apellidosNombres']); ?></td>
                            <td><a href="uploads/tareas/<?php echo htmlspecialchars($e['archivo']); ?>" target="_blank">Ver Archivo</a></td>
                            <td><input type="number" step="0.1" name="notas[<?php echo $e['idEntrega']; ?>]" class="form-control" value="<?php echo $e['nota'] ?? ''; ?>" min="0" max="20"></td>
                            <td><textarea name="observaciones[<?php echo $e['idEntrega']; ?>]" class="form-control" rows="2"><?php echo htmlspecialchars($e['observacion'] ?? ''); ?></textarea></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Guardar Calificaciones</button>
            <a href="tareas_profesor.php?idCurso=<?php echo $tarea['idCurso']; ?>" class="btn btn-secondary">Volver</a>
        </form>
    <?php else: ?>
        <div class="alert alert-info">No hay entregas registradas para esta tarea.</div>
    <?php endif; ?>
</body>
</html>
