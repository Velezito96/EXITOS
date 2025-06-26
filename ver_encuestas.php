<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || $_SESSION['idCargo'] != 3) {
    header("Location: login.php");
    exit();
}

$idCurso = $_GET['idCurso'] ?? null;
$idCliente = $_SESSION['idCliente'];

if (!$idCurso) {
    echo "Curso no especificado.";
    exit();
}

$stmt = $pdo->prepare("SELECT COUNT(*) FROM matricula WHERE idCurso = ? AND idCliente = ?");
$stmt->execute([$idCurso, $idCliente]);
if ($stmt->fetchColumn() == 0) {
    echo "No estás matriculado en este curso.";
    exit();
}

$stmt = $pdo->prepare("
    SELECT * FROM encuestas 
    WHERE idCurso = ? AND (fechaCierre IS NULL OR fechaCierre > NOW())
    ORDER BY fechaCreacion DESC
");
$stmt->execute([$idCurso]);
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
    <h3>Encuestas del Curso</h3>

    <?php foreach ($encuestas as $e): ?>
        <?php
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM encuesta_respuestas WHERE idEncuesta = ? AND idCliente = ?");
        $stmt->execute([$e['idEncuesta'], $idCliente]);
        $yaRespondio = $stmt->fetchColumn() > 0;

        $stmt = $pdo->prepare("SELECT * FROM encuesta_opciones WHERE idEncuesta = ?");
        $stmt->execute([$e['idEncuesta']]);
        $opciones = $stmt->fetchAll();
        ?>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($e['titulo']); ?></h5>
                <?php if (!empty($e['descripcion'])): ?>
                    <p class="card-text"><?php echo nl2br(htmlspecialchars($e['descripcion'])); ?></p>
                <?php endif; ?>

                <?php if ($yaRespondio): ?>
                    <div class="alert alert-success">Ya respondiste esta encuesta.</div>
                <?php else: ?>
                    <form method="post" action="responder_encuesta.php">
                        <input type="hidden" name="idEncuesta" value="<?php echo $e['idEncuesta']; ?>">
                        <?php foreach ($opciones as $o): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="<?php echo $e['multiple'] ? 'checkbox' : 'radio'; ?>" name="opciones[]" value="<?php echo $o['idOpcion']; ?>" required>
                                <label class="form-check-label"><?php echo htmlspecialchars($o['texto']); ?></label>
                            </div>
                        <?php endforeach; ?>
                        <button type="submit" class="btn btn-primary mt-3">Enviar respuesta</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <a href="panel_curso.php?idCurso=<?php echo $idCurso; ?>" class="btn btn-secondary">Volver</a>
</body>
</html>
