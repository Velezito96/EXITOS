<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || $_SESSION['idCargo'] != 3) {
    header("Location: login.php");
    exit("Acceso denegado.");
}

$idAlumno = $_SESSION['idCliente'];
$idCurso = $_GET['idCurso'] ?? null;

if (!$idCurso) {
    echo "Curso no especificado.";
    exit();
}

// Validar que el alumno esté matriculado en ese curso
$stmt = $pdo->prepare("SELECT COUNT(*) FROM matricula WHERE idCurso = ? AND idCliente = ? AND estado = 'Activo'");
$stmt->execute([$idCurso, $idAlumno]);
if ($stmt->fetchColumn() == 0) {
    echo "No estás matriculado en este curso.";
    exit();
}

// Obtener encuestas activas (no cerradas)
$stmt = $pdo->prepare("
    SELECT e.*, 
        (SELECT COUNT(*) FROM encuesta_respuestas r WHERE r.idEncuesta = e.idEncuesta AND r.idCliente = ?) as yaRespondido
    FROM encuestas e
    WHERE e.idCurso = ? AND (e.fechaCierre IS NULL OR e.fechaCierre > NOW())
    ORDER BY e.fechaCreacion DESC
");
$stmt->execute([$idAlumno, $idCurso]);
$encuestas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Encuestas del Curso</title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-4">

    <h3>Encuestas Activas</h3>

    <!-- Mostrar mensajes -->
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (empty($encuestas)): ?>
        <div class="alert alert-info">No hay encuestas disponibles en este momento.</div>
    <?php else: ?>
        <?php foreach ($encuestas as $e): ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($e['titulo']); ?></h5>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($e['descripcion'])); ?></p>

                    <?php if ($e['yaRespondido']): ?>
                        <div class="alert alert-success">Ya has respondido esta encuesta.</div>
                    <?php else: ?>
                        <!-- Mostrar opciones -->
                        <form method="post" action="responder_encuesta.php">
                            <input type="hidden" name="idEncuesta" value="<?php echo $e['idEncuesta']; ?>">
                            <input type="hidden" name="idCurso" value="<?php echo $idCurso; ?>">

                            <?php
                            $stmtOpciones = $pdo->prepare("SELECT * FROM encuesta_opciones WHERE idEncuesta = ?");
                            $stmtOpciones->execute([$e['idEncuesta']]);
                            $opciones = $stmtOpciones->fetchAll();
                            ?>

                            <?php foreach ($opciones as $op): ?>
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="<?php echo $e['multiple'] ? 'checkbox' : 'radio'; ?>" 
                                           name="opciones[]" 
                                           value="<?php echo $op['idOpcion']; ?>" 
                                           id="opcion_<?php echo $op['idOpcion']; ?>"
                                           <?php echo !$e['multiple'] ? 'required' : ''; ?>>
                                    <label class="form-check-label" for="opcion_<?php echo $op['idOpcion']; ?>">
                                        <?php echo htmlspecialchars($op['texto']); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>

                            <button type="submit" class="btn btn-primary btn-sm mt-3">Enviar Respuesta</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="panel_curso_alumno.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-secondary mt-3">Volver al Curso</a>

    <!-- Validación JS para encuestas múltiples -->
    <script>
    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", function(e) {
            const checkboxes = form.querySelectorAll("input[type='checkbox']");
            if (checkboxes.length > 0) {
                const isChecked = Array.from(checkboxes).some(cb => cb.checked);
                if (!isChecked) {
                    alert("Debes seleccionar al menos una opción antes de enviar.");
                    e.preventDefault();
                }
            }
        });
    });
    </script>
</body>
</html>
