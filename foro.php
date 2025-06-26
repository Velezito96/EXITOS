<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente'])) {
    header("Location: login.php");
    exit();
}

$idCurso = $_GET['idCurso'] ?? null;
$idUsuario = $_SESSION['idCliente'];
$idCargo = $_SESSION['idCargo'];

if (!$idCurso) {
    $_SESSION['error'] = "Curso no especificado.";
    header("Location: gestionar_cursos.php");
    exit();
}

// Validar acceso (profesor o alumno matriculado)
$stmtAcceso = $pdo->prepare("SELECT COUNT(*) FROM grupos g WHERE g.idCurso = ? AND (g.idProfesor = ? OR EXISTS (SELECT 1 FROM matricula m WHERE m.idCurso = ? AND m.idCliente = ?))");
$stmtAcceso->execute([$idCurso, $idUsuario, $idCurso, $idUsuario]);
if ($stmtAcceso->fetchColumn() == 0) {
    $_SESSION['error'] = "No tienes acceso a este curso.";
    header("Location: gestionar_cursos.php");
    exit();
}

if ($idCargo != 3): ?>
    <!-- muestra formulario de crear tema solo a no-estudiantes -->
<?php endif;

// Crear nuevo tema (profesor)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['titulo']) && $idCargo != 3) {
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'] ?? '';
    $fechaLimite = $_POST['fechaLimite'] ?? null;
    $bloquearAntes = isset($_POST['bloquearAntes']) ? 1 : 0;

    $stmt = $pdo->prepare("INSERT INTO foros (idCurso, idProfesor, titulo, descripcion, fechaLimite, bloquearAntes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$idCurso, $idUsuario, $titulo, $descripcion, $fechaLimite, $bloquearAntes]);
    $_SESSION['success'] = "Tema de debate creado.";
    header("Location: foro.php?idCurso=$idCurso");
    exit();
}

// Obtener temas de foro
$stmtTemas = $pdo->prepare("SELECT * FROM foros WHERE idCurso = ? ORDER BY fechaCreacion DESC");
$stmtTemas->execute([$idCurso]);
$temas = $stmtTemas->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Foro de Debate</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-4">
    <h2>Foro de Debate del Curso</h2>

    <?php if ($idCargo != 3): ?>
    <form method="post" class="mb-4">
        <h5>Crear nuevo tema de debate</h5>
        <div class="mb-2">
            <input type="text" name="titulo" class="form-control" placeholder="Título del debate" required>
        </div>
        <div class="mb-2">
            <textarea name="descripcion" class="form-control" rows="3" placeholder="Descripción o indicaciones"></textarea>
        </div>
        <div class="mb-2">
            <label for="fechaLimite">Fecha límite:</label>
            <input type="datetime-local" name="fechaLimite" class="form-control">
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="bloquearAntes" id="bloquearAntes">
            <label class="form-check-label" for="bloquearAntes">
                Restringir respuestas hasta que este alumno publique primero
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Crear Tema</button>
    </form>
    <?php endif; ?>

    <div class="list-group">
        <?php foreach ($temas as $t): ?>
            <a href="ver_tema.php?idForo=<?php echo $t['idForo']; ?>&idCurso=<?php echo $idCurso; ?>" class="list-group-item list-group-item-action">
                <strong><?php echo htmlspecialchars($t['titulo']); ?></strong><br>
                <small><?php echo htmlspecialchars($t['descripcion']); ?></small>
                <?php if ($t['fechaLimite']): ?>
                    <div class="text-muted">Límite: <?php echo date("d/m/Y H:i", strtotime($t['fechaLimite'])); ?></div>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>

    <a href="panel_curso.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-secondary mt-4">Volver al Panel del Curso</a>

<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>
