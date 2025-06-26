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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'] ?? '';
    $fechaInicio = $_POST['fechaInicio'];
    $fechaLimite = $_POST['fechaLimite'];
    $maxIntentos = intval($_POST['maxIntentos']);
    $permitirReenvio = isset($_POST['permitirReenvio']) ? 1 : 0;

    $stmt = $pdo->prepare("INSERT INTO tareas (idCurso, idProfesor, titulo, descripcion, fechaInicio, fechaLimite, maxIntentos, permitirReenvio) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$idCurso, $idProfesor, $titulo, $descripcion, $fechaInicio, $fechaLimite, $maxIntentos, $permitirReenvio]);

    $_SESSION['success'] = "Tarea creada correctamente.";
    header("Location: tareas_profesor.php?idCurso=$idCurso");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Nueva Tarea</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Crear Nueva Tarea</h2>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Título de la tarea</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha de inicio</label>
            <input type="datetime-local" name="fechaInicio" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Fecha límite</label>
            <input type="datetime-local" name="fechaLimite" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Máximo de intentos</label>
            <input type="number" name="maxIntentos" class="form-control" min="1" value="1" required>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="permitirReenvio" id="permitirReenvio">
            <label class="form-check-label" for="permitirReenvio">
                Permitir que el estudiante reemplace el archivo
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Tarea</button>
        <a href="tareas_profesor.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</body>
</html>
