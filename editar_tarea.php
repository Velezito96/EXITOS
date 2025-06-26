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
$stmt = $pdo->prepare("SELECT * FROM tareas WHERE idTarea = ?");
$stmt->execute([$idTarea]);
$tarea = $stmt->fetch();
if (!$tarea) {
    echo "Tarea no encontrada.";
    exit();
}

// Actualizar tarea
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'] ?? '';
    $fechaInicio = $_POST['fechaInicio'];
    $fechaLimite = $_POST['fechaLimite'];
    $maxIntentos = intval($_POST['maxIntentos']);
    $permitirReenvio = isset($_POST['permitirReenvio']) ? 1 : 0;

    $stmt = $pdo->prepare("UPDATE tareas SET titulo = ?, descripcion = ?, fechaInicio = ?, fechaLimite = ?, maxIntentos = ?, permitirReenvio = ? WHERE idTarea = ?");
    $stmt->execute([$titulo, $descripcion, $fechaInicio, $fechaLimite, $maxIntentos, $permitirReenvio, $idTarea]);

    $_SESSION['success'] = "Tarea actualizada correctamente.";
    header("Location: tareas_profesor.php?idCurso=" . $tarea['idCurso']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Tarea</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Editar Tarea</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Título de la tarea</label>
            <input type="text" name="titulo" class="form-control" value="<?php echo htmlspecialchars($tarea['titulo']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4"><?php echo htmlspecialchars($tarea['descripcion']); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha de inicio</label>
            <input type="datetime-local" name="fechaInicio" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($tarea['fechaInicio'])); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha límite</label>
            <input type="datetime-local" name="fechaLimite" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($tarea['fechaLimite'])); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Máximo de intentos</label>
            <input type="number" name="maxIntentos" class="form-control" min="1" value="<?php echo $tarea['maxIntentos']; ?>" required>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="permitirReenvio" id="permitirReenvio" <?php echo $tarea['permitirReenvio'] ? 'checked' : ''; ?>>
            <label class="form-check-label" for="permitirReenvio">
                Permitir que el estudiante reemplace el archivo
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="tareas_profesor.php?idCurso=<?php echo $tarea['idCurso']; ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>
