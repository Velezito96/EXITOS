<?php
session_start();
require 'connect.php';
$pdo = conexionBD();

if (!isset($_SESSION['idCliente']) || $_SESSION['idCargo'] != 3) {
    header("Location: login.php");
    exit();
}

$idCliente = $_SESSION['idCliente'];
$idEvaluacion = $_GET['idEvaluacion'] ?? null;

if (!$idEvaluacion) {
    echo "Evaluación no especificada.";
    exit();
}

// Obtener evaluacion y validar acceso
$stmtEval = $pdo->prepare("SELECT * FROM evaluaciones WHERE id = ?");
$stmtEval->execute([$idEvaluacion]);
evaluacion = $stmtEval->fetch();
if (!$evaluacion) {
    echo "Evaluación no encontrada.";
    exit();
}

// Obtener preguntas
$stmtPreguntas = $pdo->prepare("SELECT * FROM preguntas WHERE idEvaluacion = ?");
$stmtPreguntas->execute([$idEvaluacion]);
preguntas = $stmtPreguntas->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($evaluacion['titulo']); ?></title>
    <link rel="stylesheet" href="BOOTSTRAP/css/bootstrap.min.css">
</head>
<body class="container py-5">
    <h2><?php echo htmlspecialchars($evaluacion['titulo']); ?></h2>
    <form method="post" action="guardar_respuestas.php">
        <input type="hidden" name="idEvaluacion" value="<?php echo $idEvaluacion; ?>">
        <?php foreach ($preguntas as $i => $p): ?>
            <div class="mb-4">
                <h5><?php echo ($i + 1) . ". " . htmlspecialchars($p['texto']); ?></h5>
                <input type="hidden" name="idPregunta[]" value="<?php echo $p['idPregunta']; ?>">

                <?php if ($p['tipo'] === 'opcion'): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="respuesta_<?php echo $p['idPregunta']; ?>" value="A" required>
                        <label class="form-check-label">A) <?php echo htmlspecialchars($p['opcionA']); ?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="respuesta_<?php echo $p['idPregunta']; ?>" value="B">
                        <label class="form-check-label">B) <?php echo htmlspecialchars($p['opcionB']); ?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="respuesta_<?php echo $p['idPregunta']; ?>" value="C">
                        <label class="form-check-label">C) <?php echo htmlspecialchars($p['opcionC']); ?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="respuesta_<?php echo $p['idPregunta']; ?>" value="D">
                        <label class="form-check-label">D) <?php echo htmlspecialchars($p['opcionD']); ?></label>
                    </div>
                <?php elseif ($p['tipo'] === 'vf'): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="respuesta_<?php echo $p['idPregunta']; ?>" value="verdadero" required>
                        <label class="form-check-label">Verdadero</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="respuesta_<?php echo $p['idPregunta']; ?>" value="falso">
                        <label class="form-check-label">Falso</label>
                    </div>
                <?php elseif ($p['tipo'] === 'abierta'): ?>
                    <textarea name="respuesta_<?php echo $p['idPregunta']; ?>" class="form-control" rows="3" placeholder="Escribe tu respuesta" required></textarea>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-success">Enviar examen</button>
    </form>
<script src="BOOTSTRAP/js/bootstrap.bundle.min.js"></script>
</body>
</html>
