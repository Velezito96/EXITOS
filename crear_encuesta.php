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

// Verificar que el usuario sea profesor del curso
$stmt = $pdo->prepare("SELECT COUNT(*) FROM grupos WHERE idCurso = ? AND idProfesor = ?");
$stmt->execute([$idCurso, $idProfesor]);
if ($_SESSION['idCargo'] != 1 && $stmt->fetchColumn() == 0) {
    echo "No tienes permiso para este curso.";
    exit();
}

// Procesar formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fechaCierre = $_POST['fechaCierre'] ?: null;
    $multiple = isset($_POST['multiple']) ? 1 : 0;
    $opciones = array_filter($_POST['opciones'], fn($o) => trim($o) !== '');

    if ($titulo && count($opciones) >= 2) {
        // Insertar encuesta
        $stmt = $pdo->prepare("INSERT INTO encuestas (idCurso, idProfesor, titulo, descripcion, fechaCierre, multiple) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$idCurso, $idProfesor, $titulo, $descripcion, $fechaCierre, $multiple]);
        $idEncuesta = $pdo->lastInsertId();

        // Insertar opciones
        $stmtOpcion = $pdo->prepare("INSERT INTO encuesta_opciones (idEncuesta, texto) VALUES (?, ?)");
        foreach ($opciones as $textoOpcion) {
            $stmtOpcion->execute([$idEncuesta, trim($textoOpcion)]);
        }

        header("Location: panel_curso.php?idCurso=$idCurso");
        exit();
    } else {
        $error = "El título y al menos 2 opciones son requeridos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Encuesta</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h3>Crear Encuesta para el Curso</h3>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título de la Encuesta</label>
            <input type="text" name="titulo" id="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción (opcional)</label>
            <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="fechaCierre" class="form-label">Fecha de Cierre (opcional)</label>
            <input type="datetime-local" name="fechaCierre" id="fechaCierre" class="form-control">
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="multiple" id="multiple" class="form-check-input">
            <label for="multiple" class="form-check-label">Permitir seleccionar múltiples opciones</label>
        </div>

        <div class="mb-3">
            <label class="form-label">Opciones de respuesta</label>
            <?php for ($i = 0; $i < 5; $i++): ?>
                <input type="text" name="opciones[]" class="form-control mb-2" placeholder="Opción <?php echo $i + 1; ?>">
            <?php endfor; ?>
        </div>

        <button type="submit" class="btn btn-primary">Crear Encuesta</button>
        <a href="panel_curso.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-secondary">Cancelar</a>

        <a href="encuestas_profesor.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-outline-secondary">Ver encuestas existentes</a>

    </form>
    

    <script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>
