<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || !in_array($_SESSION['idCargo'], [1, 2])) {
    header("Location: login.php");
    exit();
}

$idCurso = $_GET['idCurso'] ?? null;
$idProfesor = $_SESSION['idCliente'];

if (!$idCurso) {
    echo "Curso no especificado.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $duracion = (int)$_POST['duracion'];
    $intentos_max = (int)$_POST['intentos_max'];

    $stmt = $pdo->prepare("INSERT INTO evaluaciones (idCurso, idProfesor, titulo, descripcion, fecha_inicio, fecha_fin, duracion, intentos_max) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$idCurso, $idProfesor, $titulo, $descripcion, $fecha_inicio, $fecha_fin, $duracion, $intentos_max]);

    header("Location: evaluaciones.php?idCurso=$idCurso");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Evaluación</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h3>Crear Nueva Evaluación</h3>

    <form method="post">
        <div class="mb-3">
            <label class="form-label">Título de la evaluación</label>
            <input type="text" name="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción / Indicaciones</label>
            <textarea name="descripcion" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha y hora de inicio</label>
            <input type="datetime-local" name="fecha_inicio" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha y hora de cierre</label>
            <input type="datetime-local" name="fecha_fin" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Duración del examen (minutos)</label>
            <input type="number" name="duracion" class="form-control" min="1" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Intentos permitidos</label>
            <input type="number" name="intentos_max" class="form-control" min="1" required>
        </div>

        <button type="submit" class="btn btn-success">Crear Evaluación</button>
        <a href="evaluaciones.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>